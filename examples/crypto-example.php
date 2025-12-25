<?php
/**
 * FCS API - Crypto Example
 * Get your API key at: https://fcsapi.com
 */

require_once __DIR__ . '/../src/FcsApi.php';
require_once __DIR__ . '/../src/FCS_Crypto.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

echo "=== Symbols List ===<br><br>";
var_dump($fcsapi->crypto->getSymbolsList('crypto', 'spot', 'BINANCE'));

echo "<br><br>=== Latest Price ===<br><br>";
var_dump($fcsapi->crypto->getLatestPrice('BINANCE:BTCUSDT'));

echo "<br><br>=== Historical Data ===<br><br>";
var_dump($fcsapi->crypto->getHistory('BINANCE:BTCUSDT', '1D', 5));

echo "<br><br>=== Profile ===<br><br>";
var_dump($fcsapi->crypto->getProfile('BTC'));
