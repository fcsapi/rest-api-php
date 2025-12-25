<?php
/**
 * FCS API - Authentication Examples
 *
 * Three authentication methods:
 * 1. access_key - Simple API key (default)
 * 2. ip_whitelist - IP whitelist (no key needed)
 * 3. token - Secure token-based (recommended for frontend)
 *
 * Get your API key at: https://fcsapi.com
 */

require_once __DIR__ . '/../src/FcsConfig.php';
require_once __DIR__ . '/../src/FcsApi.php';
require_once __DIR__ . '/../src/FCS_Forex.php';

use FcsApi\FcsApi;
use FcsApi\FcsConfig;

// ============================================================
// Method 1: Simple API Key (Backward Compatible)
// ============================================================
echo "<h3>Method 1: Simple API Key</h3>";

$fcsapi = new FcsApi('YOUR_API_KEY');
var_dump($fcsapi->forex->getLatestPrice('FX:EURUSD'));


// ============================================================
// Method 2: IP Whitelist (No key needed)
// ============================================================
echo "<br><br><h3>Method 2: IP Whitelist</h3>";

// First, whitelist your server IP in your account:
// https://fcsapi.com/dashboard/profile -> IP Whitelist

$config = FcsConfig::withIpWhitelist();
$fcsapi = new FcsApi($config);
var_dump($fcsapi->forex->getLatestPrice('FX:EURUSD'));


// ============================================================
// Method 3: Token-Based Authentication (Secure)
// ============================================================
echo "<br><br><h3>Method 3: Token-Based (Secure)</h3>";

// Step 1: On your BACKEND - Generate token
$config = FcsConfig::withToken(
    'YOUR_API_KEY',      // Private key (keep secret on server)
    'YOUR_PUBLIC_KEY',   // Public key (can be exposed)
    3600                 // Token valid for 1 hour
);

$fcsapi = new FcsApi($config);

// Generate token to send to frontend
$tokenData = $fcsapi->generateToken();
echo "<pre>Token for frontend:\n";
print_r($tokenData);
echo "</pre>";

// This token data can be sent to frontend JavaScript:
// {
//     '_token' => 'abc123...',
//     '_expiry' => 1764164233,
//     '_public_key' => 'your_public_key'
// }

// API request with token auth
var_dump($fcsapi->forex->getLatestPrice('FX:EURUSD'));


// ============================================================
// Using Config Object (Advanced)
// ============================================================
echo "<br><br><h3>Advanced: Custom Config</h3>";

$config = new FcsConfig();
$config->authMethod = 'access_key';
$config->accessKey = 'YOUR_API_KEY';
$config->timeout = 60;        // Custom timeout
$config->connectTimeout = 10; // Custom connect timeout

$fcsapi = new FcsApi($config);
var_dump($fcsapi->forex->getLatestPrice('FX:EURUSD'));
