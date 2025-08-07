# Error Page System - Manual Testing Guide

This guide provides comprehensive manual testing procedures for the error page system.

## Prerequisites

1. Ensure the Laravel application is running
2. Ensure the React frontend is compiled and served
3. Have access to browser developer tools
4. Have different browsers available for cross-browser testing

## Testing Checklist

### ✅ Basic Error Page Functionality

#### 404 - Page Not Found
- [ ] Navigate to `/nonexistent-page`
- [ ] Verify 404 page displays with orange theme
- [ ] Check "404" status code is prominently displayed
- [ ] Verify "Page Not Found" heading
- [ ] Check default error message or custom message displays
- [ ] Verify suggestions list is present and readable
- [ ] Test "Go Back" button functionality
- [ ] Test "Take me home" link navigation
- [ ] Verify error ID and timestamp are displayed
- [ ] Check support information (if configured)

#### 401 - Unauthorized
- [ ] Create a protected route and access without authentication
- [ ] Verify 401 page displays with red security theme
- [ ] Check "401" status code display
- [ ] Verify "Unauthorized Access" heading
- [ ] Check authentication-focused messaging
- [ ] Test "Sign In" button link to login page
- [ ] Test "Go Home" button functionality
- [ ] Verify error cannot be retried

#### 403 - Forbidden
- [ ] Access a route with insufficient permissions (as authenticated user)
- [ ] Verify 403 page displays
- [ ] Check "Forbidden" heading
- [ ] Verify appropriate permission-denied messaging
- [ ] Test navigation buttons

#### 419 - Page Expired (CSRF)
- [ ] Submit a form with expired/invalid CSRF token
- [ ] Verify 419 page displays
- [ ] Check "Page Expired" heading
- [ ] Verify retry functionality is available
- [ ] Test "Try Again" link works

#### 429 - Too Many Requests
- [ ] Trigger rate limiting (multiple rapid requests)
- [ ] Verify 429 page displays
- [ ] Check rate limiting message
- [ ] Verify retry is available with appropriate delay message

#### 500 - Internal Server Error
- [ ] Trigger a server error (create faulty route)
- [ ] Verify 500 page displays
- [ ] Check error logging in Laravel logs
- [ ] Verify debug information (in debug mode only)
- [ ] Check error cannot be retried

#### 503 - Service Unavailable
- [ ] Put application in maintenance mode: `php artisan down`
- [ ] Verify 503 page displays
- [ ] Check maintenance message
- [ ] Verify retry functionality
- [ ] Return application online: `php artisan up`

### ✅ Error Context and Information

#### Error Metadata
- [ ] Verify unique error ID generation
- [ ] Check timestamp accuracy and formatting
- [ ] Verify error context includes:
  - [ ] Request URL
  - [ ] HTTP method
  - [ ] User agent
  - [ ] User ID (when authenticated)

#### Debug Information (Debug Mode Only)
- [ ] Set `APP_DEBUG=true` in `.env`
- [ ] Trigger a 500 error
- [ ] Verify debug section displays:
  - [ ] Exception class
  - [ ] Error message
  - [ ] File path and line number
  - [ ] Stack trace (first 5 entries)
- [ ] Set `APP_DEBUG=false`
- [ ] Verify debug info is hidden

#### Support Information
- [ ] Configure support details in `config/errors.php`:
  ```php
  'support' => [
      'email' => 'support@example.com',
      'phone' => '+1-555-0123',
      'url' => 'https://support.example.com'
  ]
  ```
- [ ] Verify support section displays
- [ ] Test email link (`mailto:` protocol)
- [ ] Test phone link (`tel:` protocol)
- [ ] Test support center link (opens in new tab)

### ✅ User Experience and Accessibility

#### Responsive Design
- [ ] **Desktop (1200px+)**:
  - [ ] Error pages display correctly
  - [ ] Buttons are side-by-side
  - [ ] Text is readable and well-spaced
- [ ] **Tablet (768px - 1199px)**:
  - [ ] Layout adjusts appropriately
  - [ ] Content remains accessible
- [ ] **Mobile (< 768px)**:
  - [ ] Buttons stack vertically
  - [ ] Text remains readable
  - [ ] Touch targets are adequate (44px minimum)

#### Dark Mode Support
- [ ] Toggle to dark mode (if theme switcher available)
- [ ] Verify error pages adapt to dark theme:
  - [ ] Background colors adjust
  - [ ] Text contrast remains adequate
  - [ ] Icon colors adapt
  - [ ] Button styles work in dark mode

#### Accessibility
- [ ] **Keyboard Navigation**:
  - [ ] Tab through all interactive elements
  - [ ] Verify focus indicators are visible
  - [ ] Test Enter key on buttons/links
  - [ ] Verify logical tab order
- [ ] **Screen Reader**:
  - [ ] Check heading hierarchy (H1, H2, etc.)
  - [ ] Verify meaningful alt text for icons
  - [ ] Check ARIA labels where appropriate
- [ ] **Color Contrast**:
  - [ ] Use browser dev tools or contrast checker
  - [ ] Verify minimum 4.5:1 contrast ratio
  - [ ] Check both light and dark modes

### ✅ Performance and Loading

#### Load Time Testing
- [ ] Use browser dev tools Network tab
- [ ] Clear cache and reload error pages
- [ ] Verify error pages load under 2 seconds
- [ ] Check resource loading (CSS, JS, images)

#### Server-Side Rendering (SSR)
- [ ] Disable JavaScript in browser
- [ ] Navigate to error pages
- [ ] Verify content still displays (basic HTML structure)
- [ ] Re-enable JavaScript

### ✅ Cross-Browser Compatibility

Test in the following browsers:

#### Chrome/Chromium
- [ ] All error pages display correctly
- [ ] Interactive elements work
- [ ] Styling appears correct

#### Firefox
- [ ] All error pages display correctly
- [ ] Interactive elements work
- [ ] Check for Firefox-specific issues

#### Safari (if available)
- [ ] All error pages display correctly
- [ ] Interactive elements work
- [ ] Check Safari-specific styling

#### Edge
- [ ] All error pages display correctly
- [ ] Interactive elements work
- [ ] Check Edge compatibility

### ✅ Integration Testing

#### Inertia.js Integration
- [ ] Verify error pages render as Inertia responses
- [ ] Check browser back/forward navigation
- [ ] Verify page props are passed correctly

#### Laravel Exception Handler
- [ ] Check error logging in `storage/logs/laravel.log`
- [ ] Verify different error types are handled
- [ ] Test with different HTTP Accept headers

#### Theme Integration
- [ ] Verify Mantine components render correctly
- [ ] Check Tailwind CSS classes apply
- [ ] Test theme consistency with rest of application

### ✅ Edge Cases and Error Scenarios

#### Network Conditions
- [ ] **Slow Connection**:
  - [ ] Throttle network in dev tools (Slow 3G)
  - [ ] Verify error pages still load acceptably
- [ ] **Offline**:
  - [ ] Go offline in dev tools
  - [ ] Verify appropriate error handling

#### Malformed Requests
- [ ] Send malformed data to endpoints
- [ ] Verify appropriate error responses
- [ ] Check error page renders correctly

#### Large Error Data
- [ ] Trigger errors with large stack traces
- [ ] Verify error pages handle large debug data
- [ ] Check performance with large error context

## Error Simulation Commands

Use these commands to trigger specific errors for testing:

```bash
# 404 - Navigate to any non-existent route
curl -H "Accept: text/html" http://localhost:8000/nonexistent

# 500 - Create a test route that throws an exception
php artisan route:list | grep test-error

# 503 - Enable maintenance mode
php artisan down --render="errors::503"

# 419 - Submit form with invalid CSRF token
curl -X POST http://localhost:8000/test-form -H "X-CSRF-TOKEN: invalid"
```

## Expected Results Summary

| Error Code | Expected Page | Retry Available | Theme Color |
|------------|---------------|-----------------|-------------|
| 400        | Bad Request   | No              | Orange      |
| 401        | Unauthorized  | No              | Red         |
| 403        | Forbidden     | No              | Red         |
| 404        | Not Found     | No              | Orange      |
| 419        | Page Expired  | Yes             | Yellow      |
| 429        | Rate Limited  | Yes (delayed)   | Yellow      |
| 500        | Server Error  | No              | Red         |
| 503        | Maintenance   | Yes             | Blue        |

## Common Issues and Solutions

### Error Page Not Displaying
- Check `config/errors.php` configuration
- Verify error code is in `renderable_errors` array
- Check Inertia.js middleware is working

### Styling Issues
- Verify Tailwind CSS compilation
- Check Mantine theme configuration
- Ensure CSS assets are loaded

### JavaScript Errors
- Check browser console for errors
- Verify React components compile correctly
- Check for missing dependencies

## Reporting Issues

When reporting issues, include:

1. **Error Details**:
   - Error code and type
   - Browser and version
   - Screen size/device
   - Steps to reproduce

2. **Environment**:
   - Laravel version
   - Node.js version
   - Debug mode status
   - Configuration details

3. **Evidence**:
   - Screenshots
   - Console errors
   - Network tab details
   - Error logs
