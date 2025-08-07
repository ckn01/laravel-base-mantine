<?php

namespace App\Http\Controllers;

use App\Support\ErrorHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ErrorReportController extends Controller
{
    /**
     * Report a client-side error.
     */
    public function report(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'stack' => 'nullable|string|max:5000',
            'url' => 'required|url|max:500',
            'line' => 'nullable|integer',
            'column' => 'nullable|integer',
            'userAgent' => 'nullable|string|max:500',
            'timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid error report data',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (! config('errors.reporting.log_client_errors', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Client error reporting is disabled',
            ], 403);
        }

        $errorData = $validator->validated();
        ErrorHelper::logClientError($errorData, $request);

        return response()->json([
            'success' => true,
            'message' => 'Error reported successfully',
            'reportId' => ErrorHelper::generateErrorId(),
        ]);
    }

    /**
     * Get error reporting configuration for the client.
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'enabled' => config('errors.reporting.log_client_errors', false),
            'features' => config('errors.features', []),
            'supportInfo' => config('errors.support', []),
        ]);
    }
}