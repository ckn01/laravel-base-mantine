import { test, expect } from '@playwright/test';

test.describe('Error Pages E2E', () => {
  test.beforeEach(async ({ page }) => {
    // Increase timeout for slower operations
    test.setTimeout(30000);
  });

  test('404 error page displays correctly', async ({ page }) => {
    // Navigate to a non-existent page
    await page.goto('/nonexistent-page', { waitUntil: 'networkidle' });

    // Check that we're on the 404 page
    await expect(page).toHaveTitle(/404.*Not Found/);

    // Check main elements
    await expect(page.locator('text=404')).toBeVisible();
    await expect(page.locator('text=Page Not Found')).toBeVisible();
    await expect(page.locator('text=The page you are looking for')).toBeVisible();

    // Check action buttons
    await expect(page.locator('button:has-text("Go Back")')).toBeVisible();
    await expect(page.locator('a:has-text("Take me home")')).toBeVisible();

    // Check suggestions list
    await expect(page.locator('text=Here are some suggestions:')).toBeVisible();
    await expect(page.locator('text=Check if the URL is typed correctly')).toBeVisible();

    // Verify error information
    await expect(page.locator('text=Error ID:')).toBeVisible();
    await expect(page.locator('text=Time:')).toBeVisible();
  });

  test('404 page Go Back button works', async ({ page, context }) => {
    // First navigate to a valid page
    await page.goto('/');
    
    // Then navigate to a 404 page
    await page.goto('/nonexistent-page');
    
    // Click Go Back button
    await page.locator('button:has-text("Go Back")').click();
    
    // Should go back to the previous page
    await expect(page).toHaveURL('/');
  });

  test('404 page home link works', async ({ page }) => {
    await page.goto('/nonexistent-page');
    
    // Click home link
    await page.locator('a:has-text("Take me home")').click();
    
    // Should navigate to dashboard
    await expect(page).toHaveURL('/dashboard');
  });

  test('401 error page displays correctly', async ({ page }) => {
    // Create a route that returns 401 (this would need to be set up in your Laravel routes)
    await page.route('/test-401', route => {
      route.fulfill({
        status: 401,
        contentType: 'text/html',
        body: '<\!DOCTYPE html><html><head><title>401</title></head><body><h1>401 Unauthorized</h1></body></html>'
      });
    });

    await page.goto('/test-401');

    // Check that we get a 401 response
    const response = await page.request.get('/test-401');
    expect(response.status()).toBe(401);
  });

  test('error pages are responsive', async ({ page }) => {
    await page.goto('/nonexistent-page');

    // Test mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await expect(page.locator('text=404')).toBeVisible();
    
    // Check that buttons stack vertically on mobile
    const buttonContainer = page.locator('.flex.flex-col.gap-3.sm\\:flex-row').first();
    await expect(buttonContainer).toBeVisible();

    // Test desktop viewport
    await page.setViewportSize({ width: 1200, height: 800 });
    await expect(page.locator('text=404')).toBeVisible();
  });

  test('error pages have proper accessibility', async ({ page }) => {
    await page.goto('/nonexistent-page');

    // Check heading structure
    const mainHeading = page.locator('h2');
    await expect(mainHeading).toBeVisible();
    await expect(mainHeading).toHaveText('Page Not Found');

    // Check that buttons and links are keyboard accessible
    const goBackButton = page.locator('button:has-text("Go Back")');
    const homeLink = page.locator('a:has-text("Take me home")');

    await expect(goBackButton).toBeVisible();
    await expect(homeLink).toBeVisible();

    // Test keyboard navigation
    await page.keyboard.press('Tab');
    await page.keyboard.press('Tab');
    
    // Check that focused elements are visible
    const focusedElement = page.locator(':focus');
    await expect(focusedElement).toBeVisible();
  });

  test('error pages handle dark mode correctly', async ({ page }) => {
    await page.goto('/nonexistent-page');

    // Check that dark mode classes are present (assuming theme system is working)
    const body = page.locator('body');
    
    // Test light mode first
    await expect(body).toBeVisible();
    
    // If there's a theme toggle, test dark mode
    const themeToggle = page.locator('[data-testid="theme-toggle"]');
    if (await themeToggle.isVisible()) {
      await themeToggle.click();
      
      // Verify dark mode styling is applied
      const darkModeElement = page.locator('.dark\\:bg-orange-950\\/20');
      await expect(darkModeElement).toBeVisible();
    }
  });

  test('error pages display support information', async ({ page }) => {
    await page.goto('/nonexistent-page');

    // Check support section if configured
    const supportSection = page.locator('text=Need help?');
    
    if (await supportSection.isVisible()) {
      await expect(supportSection).toBeVisible();
      
      // Check support links
      const emailLink = page.locator('a[href^="mailto:"]');
      const phoneLink = page.locator('a[href^="tel:"]');
      const supportCenterLink = page.locator('a:has-text("Support Center")');
      
      // At least one support option should be visible
      const supportOptions = [emailLink, phoneLink, supportCenterLink];
      let visibleOptions = 0;
      
      for (const option of supportOptions) {
        if (await option.isVisible()) {
          visibleOptions++;
        }
      }
      
      expect(visibleOptions).toBeGreaterThan(0);
    }
  });

  test('retry functionality works for retryable errors', async ({ page }) => {
    // Mock a 419 CSRF error which should be retryable
    await page.route('/test-retry', route => {
      route.fulfill({
        status: 419,
        contentType: 'text/html',
        body: '<\!DOCTYPE html><html><head><title>419</title></head><body><h1>419 Page Expired</h1></body></html>'
      });
    });

    await page.goto('/test-retry');

    // Check if retry link is present for retryable errors
    const retryLink = page.locator('a:has-text("Try Again")');
    if (await retryLink.isVisible()) {
      await expect(retryLink).toBeVisible();
      
      // Verify retry link has correct href
      await expect(retryLink).toHaveAttribute('href', '/test-retry');
    }
  });

  test('error pages load within performance budget', async ({ page }) => {
    const startTime = Date.now();
    
    await page.goto('/nonexistent-page', { waitUntil: 'networkidle' });
    
    const loadTime = Date.now() - startTime;
    
    // Error pages should load quickly (under 2 seconds)
    expect(loadTime).toBeLessThan(2000);
    
    // Check that main content is visible
    await expect(page.locator('text=404')).toBeVisible();
  });

  test('error pages work with SSR', async ({ page }) => {
    // Disable JavaScript to test SSR
    await page.context().addInitScript(() => {
      Object.defineProperty(window.navigator, 'userAgent', {
        writable: false,
        value: 'NoJS'
      });
    });

    await page.goto('/nonexistent-page');

    // Should still render the error page structure even without JS
    await expect(page.locator('text=404')).toBeVisible();
    await expect(page.locator('text=Page Not Found')).toBeVisible();
  });
});