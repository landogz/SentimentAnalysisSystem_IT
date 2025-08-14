<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LicenseService
{
    protected $licenseApiUrl = 'https://landogzwebsolutions.com/landogzwebsolutions.json';
    protected $cacheKey = 'license_validation';
    protected $cacheDuration = 60; // 1 minute - very short cache for immediate expiry detection

    /**
     * Get license key from environment
     */
    public function getLicenseKey(): ?string
    {
        return env('LICENSE_KEY');
    }

    /**
     * Set license key in environment
     */
    public function setLicenseKey(string $licenseKey): bool
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            return false;
        }

        $content = file_get_contents($envFile);
        
        // Check if LICENSE_KEY already exists
        if (strpos($content, 'LICENSE_KEY=') !== false) {
            // Update existing license key
            $content = preg_replace('/LICENSE_KEY=.*/', 'LICENSE_KEY=' . $licenseKey, $content);
        } else {
            // Add new license key
            $content .= "\nLICENSE_KEY=" . $licenseKey;
        }

        return file_put_contents($envFile, $content) !== false;
    }

    /**
     * Fetch licenses from external API
     */
    public function fetchLicenses(): array
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'ESP-CIT-SentimentAnalysis/1.0',
                    'Accept' => 'application/json'
                ])
                ->get($this->licenseApiUrl);
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('Successfully fetched licenses', ['count' => count($data)]);
                return $data;
            }
            
            Log::error('Failed to fetch licenses from API', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $this->licenseApiUrl
            ]);
            
            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching licenses', [
                'message' => $e->getMessage(),
                'url' => $this->licenseApiUrl,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [];
        }
    }

    /**
     * Validate license key (ALWAYS uses fresh data from API - NO CACHE)
     */
    public function validateLicense(string $licenseKey): array
    {
        // Always fetch fresh data from API - NO CACHE
        $licenses = $this->fetchLicenses();
        
        foreach ($licenses as $license) {
            if ($license['license_id'] === $licenseKey) {
                // Check if the system name matches "Sentiment Analysis System"
                if ($license['system_name'] !== 'Sentiment Analysis System') {
                    return [
                        'valid' => false,
                        'license' => null,
                        'message' => 'Invalid license key - System name mismatch'
                    ];
                }
                
                return [
                    'valid' => true,
                    'license' => $license,
                    'message' => 'License is valid'
                ];
            }
        }

        return [
            'valid' => false,
            'license' => null,
            'message' => 'Invalid license key'
        ];
    }

    /**
     * Check if current license is valid (ALWAYS uses fresh data from API)
     */
    public function isLicenseValid(): bool
    {
        $licenseKey = $this->getLicenseKey();
        
        if (!$licenseKey) {
            return false;
        }

        // Always fetch fresh data from API - NO CACHE
        $validation = $this->validateLicense($licenseKey);
        
        if (!$validation['valid']) {
            return false;
        }

        $license = $validation['license'];
        
        // Check if license is active
        if ($license['status'] !== 'Active') {
            return false;
        }

        // Check if license is expired using fresh data
        if ($this->isLicenseExpiredFresh($licenseKey)) {
            return false;
        }

        return true;
    }

    /**
     * Get current license information (ALWAYS uses fresh data from API)
     */
    public function getCurrentLicense(): ?array
    {
        $licenseKey = $this->getLicenseKey();
        
        if (!$licenseKey) {
            return null;
        }

        // Always fetch fresh data from API - NO CACHE
        $validation = $this->validateLicense($licenseKey);
        
        if ($validation['valid']) {
            return $validation['license'];
        }

        return null;
    }

    /**
     * Clear license cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Check if license is expired (ALWAYS uses fresh data from API)
     */
    public function isLicenseExpired(): bool
    {
        $license = $this->getCurrentLicense();
        
        if (!$license) {
            return true;
        }

        // Lifetime licenses don't expire
        if ($license['license_type'] === 'Lifetime') {
            return false;
        }

        // Check if expiry date is set and has passed
        if ($license['expiry_date']) {
            $expiryDate = \Carbon\Carbon::parse($license['expiry_date']);
            return now()->isAfter($expiryDate);
        }

        return false;
    }

    /**
     * Validate license key with fresh data from API (no cache) - DEPRECATED, use validateLicense() instead
     */
    public function validateLicenseFresh(string $licenseKey): array
    {
        // This method is now deprecated - always use validateLicense() which fetches fresh data
        return $this->validateLicense($licenseKey);
    }

    /**
     * Check if license is expired with fresh data from API - DEPRECATED, use isLicenseExpired() instead
     */
    public function isLicenseExpiredFresh(string $licenseKey): bool
    {
        // This method is now deprecated - always use isLicenseExpired() which fetches fresh data
        return $this->isLicenseExpired();
    }
} 