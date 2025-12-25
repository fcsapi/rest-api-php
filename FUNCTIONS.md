# FCS API - PHP Functions Reference

Quick reference for all available functions in the FCS API PHP library.

---

## Authentication Methods

```php
use FcsApi\FcsApi;
use FcsApi\FcsConfig;

// Method 1: Default (uses key from FcsConfig.php)
$fcsapi = new FcsApi();

// Method 2: Pass API Key directly (override)
$fcsapi = new FcsApi('YOUR_API_KEY');

// Method 3: IP Whitelist (no key needed if IP whitelisted in account)
$config = FcsConfig::withIpWhitelist();
$fcsapi = new FcsApi($config);

// Method 4: Token-Based (secure for frontend apps)
$config = FcsConfig::withToken('API_KEY', 'PUBLIC_KEY', 3600);
$fcsapi = new FcsApi($config);
$tokenData = $fcsapi->generateToken(); // Send to frontend
```

### Set Default API Key
Edit `src/FcsConfig.php` and set your key:
```php
public string $accessKey = 'YOUR_API_KEY_HERE';
```

### Token Expiry Values
| Seconds | Duration |
|---------|----------|
| 300 | 5 minutes |
| 900 | 15 minutes |
| 1800 | 30 minutes |
| 3600 | 1 hour |
| 86400 | 24 hours |

---

## Crypto Functions

```php
$fcsapi->crypto->getSymbolsList($type, $subType, $exchange)
$fcsapi->crypto->getCoinsList()
$fcsapi->crypto->getLatestPrice($symbol, $period, $type, $exchange, $getProfile)
$fcsapi->crypto->getAllPrices($exchange, $period, $type)
$fcsapi->crypto->getCoinData($symbol, $limit, $sortBy)
$fcsapi->crypto->getTopByMarketCap($limit)
$fcsapi->crypto->getTopByRank($limit)
$fcsapi->crypto->convert($pair1, $pair2, $amount)
$fcsapi->crypto->getBasePrices($symbol, $exchange, $fallback)
$fcsapi->crypto->getCrossRates($symbol, $exchange, $type, $period, $crossrates, $fallback)
$fcsapi->crypto->getHistory($symbol, $period, $length, $from, $to, $page, $isChart)
$fcsapi->crypto->getProfile($symbol)
$fcsapi->crypto->getExchanges($type, $subType)
$fcsapi->crypto->advanced($params)
$fcsapi->crypto->getMovingAverages($symbol, $period, $exchange)
$fcsapi->crypto->getIndicators($symbol, $period, $exchange)
$fcsapi->crypto->getPivotPoints($symbol, $period, $exchange)
$fcsapi->crypto->getPerformance($symbol, $exchange)
$fcsapi->crypto->getTopGainers($exchange, $limit, $period, $type)
$fcsapi->crypto->getTopLosers($exchange, $limit, $period, $type)
$fcsapi->crypto->getHighestVolume($exchange, $limit, $period, $type)
$fcsapi->crypto->getSortedData($sortColumn, $sortDirection, $limit, $type, $exchange, $period)
$fcsapi->crypto->search($query, $type)
$fcsapi->crypto->multiUrl($urls, $base)
```

### Parameters
| Parameter | Values |
|-----------|--------|
| `$type` | crypto, coin, futures, dex, dominance |
| `$subType` | spot, swap, index |
| `$exchange` | BINANCE, COINBASE, KRAKEN, BYBIT |
| `$period` | 1m, 5m, 15m, 30m, 1h, 4h, 1D, 1W, 1M |
| `$sortBy` | perf.rank_asc, perf.market_cap_desc, perf.circulating_supply_desc |
| `$sortColumn` | active.c, active.chp, active.v, active.h, active.l, perf.rank, perf.market_cap |
| `$sortDirection` | asc, desc |

---

## Forex Functions

```php
$fcsapi->forex->getSymbolsList($type, $subType, $exchange)
$fcsapi->forex->getLatestPrice($symbol, $period, $type, $exchange, $getProfile)
$fcsapi->forex->getAllPrices($exchange, $period, $type)
$fcsapi->forex->getCommodities($symbol, $period)
$fcsapi->forex->getCommoditySymbols()
$fcsapi->forex->convert($pair1, $pair2, $amount, $type)
$fcsapi->forex->getBasePrices($symbol, $type, $exchange, $fallback)
$fcsapi->forex->getCrossRates($symbol, $type, $period, $exchange, $crossrates, $fallback)
$fcsapi->forex->getHistory($symbol, $period, $length, $from, $to, $page, $isChart)
$fcsapi->forex->getProfile($symbol)
$fcsapi->forex->getExchanges($type, $subType)
$fcsapi->forex->advanced($params)
$fcsapi->forex->getMovingAverages($symbol, $period, $exchange)
$fcsapi->forex->getIndicators($symbol, $period, $exchange)
$fcsapi->forex->getPivotPoints($symbol, $period, $exchange)
$fcsapi->forex->getPerformance($symbol, $exchange)
$fcsapi->forex->getEconomyCalendar($symbol, $country, $from, $to)
$fcsapi->forex->getTopGainers($type, $limit, $period, $exchange)
$fcsapi->forex->getTopLosers($type, $limit, $period, $exchange)
$fcsapi->forex->getMostActive($type, $limit, $period, $exchange)
$fcsapi->forex->getSortedData($sortColumn, $sortDirection, $limit, $type, $exchange, $period)
$fcsapi->forex->search($query, $type, $exchange)
$fcsapi->forex->multiUrl($urls, $base)
```

### Parameters
| Parameter | Values |
|-----------|--------|
| `$type` | forex, commodity |
| `$subType` | spot, synthetic |
| `$exchange` | FX, ONA, SFO, FCM |
| `$period` | 1m, 5m, 15m, 30m, 1h, 4h, 1D, 1W, 1M |
| `$country` | US, GB, DE, JP, AU, CA |

---

## Stock Functions

```php
// Symbol/List
$fcsapi->stock->getSymbolsList($exchange, $country, $sector, $indices)
$fcsapi->stock->search($query, $exchange, $country)

// Indices
$fcsapi->stock->getIndicesList($country, $exchange)
$fcsapi->stock->getIndicesLatest($symbol, $country, $exchange)

// Latest Prices
$fcsapi->stock->getLatestPrice($symbol, $period, $exchange, $getProfile)
$fcsapi->stock->getAllPrices($exchange, $period)
$fcsapi->stock->getLatestByCountry($country, $sector, $period)
$fcsapi->stock->getLatestByIndices($indices, $period)

// Historical
$fcsapi->stock->getHistory($symbol, $period, $length, $from, $to, $page, $isChart)

// Profile & Info
$fcsapi->stock->getProfile($symbol)
$fcsapi->stock->getExchanges($type, $subType)

// Financial Data
$fcsapi->stock->getEarnings($symbol, $duration)
$fcsapi->stock->getRevenue($symbol)
$fcsapi->stock->getDividends($symbol, $format)
$fcsapi->stock->getBalanceSheet($symbol, $duration, $format)
$fcsapi->stock->getIncomeStatements($symbol, $duration, $format)
$fcsapi->stock->getCashFlow($symbol, $duration, $format)
$fcsapi->stock->getStatistics($symbol, $duration)
$fcsapi->stock->getForecast($symbol)
$fcsapi->stock->getStockData($symbol, $dataColumn, $duration, $format)

// Technical Analysis
$fcsapi->stock->getMovingAverages($symbol, $period)
$fcsapi->stock->getIndicators($symbol, $period)
$fcsapi->stock->getPivotPoints($symbol, $period)
$fcsapi->stock->getPerformance($symbol)

// Top Movers & Sorting
$fcsapi->stock->getTopGainers($exchange, $limit, $period, $country)
$fcsapi->stock->getTopLosers($exchange, $limit, $period, $country)
$fcsapi->stock->getMostActive($exchange, $limit, $period, $country)
$fcsapi->stock->getSortedData($sortColumn, $sortDirection, $limit, $exchange, $country, $period)

// Filter
$fcsapi->stock->getBySector($sector, $limit, $exchange)
$fcsapi->stock->getByCountry($country, $limit, $exchange)

// Advanced
$fcsapi->stock->advanced($params)
$fcsapi->stock->multiUrl($urls, $base)
```

### Parameters
| Parameter | Values |
|-----------|--------|
| `$type` | stock, index, fund, structured, dr |
| `$subType` | spot, main, cfd, common, preferred |
| `$exchange` | NASDAQ, NYSE, LSE, TSE, HKEX, BSE |
| `$period` | 1m, 5m, 15m, 30m, 1h, 4h, 1D, 1W, 1M |
| `$duration` | annual, interim, both |
| `$format` | plain, inherit |
| `$dataColumn` | earnings, revenue, profile, dividends, balance_sheet, income_statements, statistics, cash_flow |

---

## Common Response Fields

| Field | Description |
|-------|-------------|
| `o` | Open price |
| `h` | High price |
| `l` | Low price |
| `c` | Close/Current price |
| `v` | Volume |
| `t` | Unix timestamp |
| `ch` | Change amount |
| `chp` | Change percentage |

---

## Quick Examples

```php
// Initialize (uses key from FcsConfig.php)
$fcsapi = new FcsApi();

// Crypto
$fcsapi->crypto->getLatestPrice('BINANCE:BTCUSDT');
$fcsapi->crypto->getHistory('BINANCE:BTCUSDT', '1D', 100);
$fcsapi->crypto->getCoinData(null, 50, 'perf.rank_asc');

// Forex
$fcsapi->forex->getLatestPrice('FX:EURUSD');
$fcsapi->forex->convert('EUR', 'USD', 100);

// Stock
$fcsapi->stock->getLatestPrice('NASDAQ:AAPL');
$fcsapi->stock->getTopGainers('NASDAQ', 10);
$fcsapi->stock->getEarnings('NASDAQ:AAPL', 'annual');
$fcsapi->stock->getDividends('NASDAQ:AAPL');
$fcsapi->stock->getBalanceSheet('NASDAQ:AAPL', 'annual');
$fcsapi->stock->getStockData('NASDAQ:AAPL', 'profile,earnings,dividends');
```

---

## Token Authentication Example

```php
// Backend: Generate token
$config = FcsConfig::withToken('YOUR_API_KEY', 'YOUR_PUBLIC_KEY', 3600);
$fcsapi = new FcsApi($config);
$tokenData = $fcsapi->generateToken();

// Send $tokenData to frontend:
// {
//     '_token' => 'abc123...',
//     '_expiry' => 1764164233,
//     '_public_key' => 'your_public_key'
// }

// Frontend (JavaScript): Use token
// fetch('https://api-v4.fcsapi.com/forex/latest?symbol=EURUSD' +
//       '&_public_key=' + tokenData._public_key +
//       '&_expiry=' + tokenData._expiry +
//       '&_token=' + tokenData._token)
```

---

## Get API Key

Get your API key at: https://fcsapi.com
