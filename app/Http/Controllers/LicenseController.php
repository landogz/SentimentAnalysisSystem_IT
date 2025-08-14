<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Artisan;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Display the license entry page
     */
    public function index()
    {
        $currentLicense = $this->licenseService->getCurrentLicense();
        $isValid = $this->licenseService->isLicenseValid();
        $isExpired = $this->licenseService->isLicenseExpired();

        return view('license.index', compact('currentLicense', 'isValid', 'isExpired'));
    }

    /**
     * Store the license key
     */
    public function store(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|max:255'
        ]);

        $licenseKey = trim($request->license_key);

        // Validate the license key with fresh data from API
        $validation = $this->licenseService->validateLicense($licenseKey);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid license key. Please check and try again.',
                'type' => 'error'
            ]);
        }

        $license = $validation['license'];

        // Check if license is active
        if ($license['status'] !== 'Active') {
            return response()->json([
                'success' => false,
                'message' => 'License is not active. Please contact support.',
                'type' => 'warning',
                'license' => $license
            ]);
        }

        // Check if license is expired
        if ($license['license_type'] !== 'Lifetime' && $license['expiry_date']) {
            $expiryDate = \Carbon\Carbon::parse($license['expiry_date']);
            if (now()->isAfter($expiryDate)) {
                return response()->json([
                    'success' => false,
                    'message' => 'License has expired. Please renew your license.',
                    'type' => 'warning',
                    'license' => $license,
                    'expired' => true
                ]);
            }
        }

        // Save the license key to .env file
        if (!$this->licenseService->setLicenseKey($licenseKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save license key. Please check file permissions.',
                'type' => 'error'
            ]);
        }

        // Clear cache
        $this->licenseService->clearCache();

        // Clear config cache to reload .env
        Artisan::call('config:clear');

        return response()->json([
            'success' => true,
            'message' => 'License key has been successfully activated!',
            'type' => 'success',
            'license' => $license,
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Test license validation (AJAX)
     */
    public function test(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|max:255'
        ]);

        $licenseKey = trim($request->license_key);
        $validation = $this->licenseService->validateLicense($licenseKey);

        return response()->json($validation);
    }


} 