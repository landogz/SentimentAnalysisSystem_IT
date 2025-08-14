<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LicenseService;
use Symfony\Component\HttpFoundation\Response;

class LicenseMiddleware
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip license check for license-related routes
        if ($request->is('license*') || $request->is('admin/license*')) {
            return $next($request);
        }

        // Check if license is valid (includes expiry check)
        if (!$this->licenseService->isLicenseValid()) {
            // If it's an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'License validation required',
                    'redirect' => route('license.index')
                ], 403);
            }

            // Redirect to license page with appropriate message
            $licenseKey = $this->licenseService->getLicenseKey();
            $message = '';
            
            if ($licenseKey && $this->licenseService->isLicenseExpired()) {
                $message = 'Your license has expired. Please renew your license. Contact system developer for assistance.';
            } else {
                $message = 'Please enter a valid license key to continue. Contact system developer if you need help.';
            }

            // Clear any existing cache to ensure fresh data
            $this->licenseService->clearCache();
            
            return redirect()->route('license.index')->with('error', $message);
        }

        return $next($request);
    }
} 