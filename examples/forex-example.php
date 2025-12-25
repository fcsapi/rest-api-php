<?php
/**
 * FCS API - Forex Example
 * Get your API key at: https://fcsapi.com
 */

require_once __DIR__ . '/../src/FcsApi.php';
require_once __DIR__ . '/../src/FCS_Forex.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

echo "=== Symbols List ===<br><br>";
var_dump($fcsapi->forex->getSymbolsList('forex', 'spot'));

echo "<br><br>=== Latest Price ===<br><br>";
var_dump($fcsapi->forex->getLatestPrice('FX:EURUSD'));

echo "<br><br>=== Historical Data ===<br><br>";
var_dump($fcsapi->forex->getHistory('EURUSD', '1D', 5));

echo "<br><br>=== Profile ===<br><br>";
var_dump($fcsapi->forex->getProfile('EUR'));
