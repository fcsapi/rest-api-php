<?php
/**
 * FCS API - Stock Example
 * Get your API key at: https://fcsapi.com
 */

require_once __DIR__ . '/../src/FcsApi.php';
require_once __DIR__ . '/../src/FCS_Stock.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

echo "=== Symbols List ===<br><br>";
var_dump($fcsapi->stock->getSymbolsList('NASDAQ'));

echo "<br><br>=== Latest Price ===<br><br>";
var_dump($fcsapi->stock->getLatestPrice('NASDAQ:AAPL'));

echo "<br><br>=== Historical Data ===<br><br>";
var_dump($fcsapi->stock->getHistory('NASDAQ:AAPL', '1D', 5));

echo "<br><br>=== Profile ===<br><br>";
var_dump($fcsapi->stock->getProfile('AAPL'));
