<?php
/**
 * FCS API - Configuration
 *
 * Authentication options:
 * 1. access_key - Simple API key authentication
 * 2. ip_whitelist - No key needed if IP is whitelisted in your account https://fcsapi.com/dashboard/profile
 * 3. token - Secure token-based authentication (recommended for frontend)
 *
 * @package FcsApi
 * @author FCS API <support@fcsapi.com>
 */

namespace FcsApi;

class FcsConfig
{
    /**
     * Authentication method
     * Options: 'access_key', 'ip_whitelist', 'token'
     */
    public string $authMethod = 'access_key';

    /**
     * API Access Key (Private Key)
     * Get from: https://fcsapi.com/dashboard
     */
    public string $accessKey = 'YOUR_ACCESS_KEY_HERE';

    /**
     * Public Key (for token-based auth)
     * Get from: https://fcsapi.com/dashboard
     */
    public string $publicKey = 'YOUR_PUBLIC_KEY_HERE';

    /**
     * Token expiry time in seconds
     * Default: 3600 (1 hour)
     * Common values: 300 (5min), 900 (15min), 1800 (30min), 3600 (1hr), 86400 (24hr)
     */
    public int $tokenExpiry = 3600;

    /**
     * Request timeout in seconds
     */
    public int $timeout = 30;

    /**
     * Connection timeout in seconds
     */
    public int $connectTimeout = 5;

    /**
     * Create config with access_key method
     */
    public static function withAccessKey(string $accessKey): self
    {
        $config = new self();
        $config->authMethod = 'access_key';
        $config->accessKey = $accessKey;
        return $config;
    }

    /**
     * Create config with IP whitelist method (no key needed)
     */
    public static function withIpWhitelist(): self
    {
        $config = new self();
        $config->authMethod = 'ip_whitelist';
        return $config;
    }

    /**
     * Create config with token-based authentication
     *
     * @param string $accessKey Your private API key (kept on server)
     * @param string $publicKey Your public key
     * @param int $tokenExpiry Token validity in seconds
     */
    public static function withToken(string $accessKey, string $publicKey, int $tokenExpiry = 3600): self
    {
        $config = new self();
        $config->authMethod = 'token';
        $config->accessKey = $accessKey;
        $config->publicKey = $publicKey;
        $config->tokenExpiry = $tokenExpiry;
        return $config;
    }

    /**
     * Generate authentication token
     * Use this on your backend, then send token to frontend
     *
     * @return array ['_token' => string, '_expiry' => int, '_public_key' => string]
     */
    public function generateToken(): array
    {
        $expiry = time() + $this->tokenExpiry;
        $message = $this->publicKey . $expiry;
        $token = hash_hmac('sha256', $message, $this->accessKey);

        return [
            '_token' => $token,
            '_expiry' => $expiry,
            '_public_key' => $this->publicKey
        ];
    }

    /**
     * Get authentication parameters for API request
     *
     * @return array
     */
    public function getAuthParams(): array
    {
        switch ($this->authMethod) {
            case 'ip_whitelist':
                return [];

            case 'token':
                return $this->generateToken();

            case 'access_key':
            default:
                return ['access_key' => $this->accessKey];
        }
    }
}
