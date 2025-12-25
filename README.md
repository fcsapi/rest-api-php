# FCSAPI - PHP REST Client

**PHP** REST API client library for **Forex**, **Cryptocurrency**, and **Stock** market data from [FCS API](https://fcsapi.com).

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![Packagist](https://img.shields.io/packagist/v/fcsapi/rest-api.svg)](https://packagist.org/packages/fcsapi/rest-api)

## Features

- **Forex API** - 4000+ currency pairs, real-time rates, commodities, historical data, technical analysis
- **Crypto API** - 50,000+ coins from major exchanges (Binance, Coinbase, etc.), market cap, rank, coin data
- **Stock API** - 125,000+ global stocks, indices, earnings, financials, dividends
- **Easy to Use** - Simple method calls for all API endpoints
- **Multiple Auth Methods** - API key, IP whitelist, or secure token-based authentication
- **PSR-4 Autoloading** - Composer compatible

## Installation

### Composer (Recommended)
```bash
composer require fcsapi/rest-api
```

### Manual Installation
1. Download or clone this repository
2. Include the autoloader or require files manually

```php
require_once 'path/to/rest-api-php/src/FcsApi.php';
require_once 'path/to/rest-api-php/src/Forex.php';  // For Forex
require_once 'path/to/rest-api-php/src/Crypto.php'; // For Crypto
require_once 'path/to/rest-api-php/src/Stock.php';  // For Stock
```

## Quick Start

```php
<?php
require 'vendor/autoload.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

// Forex
$response = $fcsapi->forex->getLatestPrice('EURUSD');

// Crypto
$response = $fcsapi->crypto->getLatestPrice('BINANCE:BTCUSDT');

// Stock
$response = $fcsapi->stock->getLatestPrice('NASDAQ:AAPL');
```

## Authentication Methods

The library supports 4 authentication methods for different security needs:

### Method 1: Default Configuration (Recommended)
Set your API key once in `src/FcsConfig.php`:
```php
public string $accessKey = 'YOUR_API_KEY_HERE';
```
Then simply use:
```php
$fcsapi = new FcsApi();
```

### Method 2: Direct API Key
Pass API key directly (overrides config):
```php
$fcsapi = new FcsApi('YOUR_API_KEY');
```

### Method 3: IP Whitelist (No Key Required)
Whitelist your server IP at [FCS Dashboard](https://fcsapi.com/dashboard/profile):
```php
use FcsApi\FcsConfig;

$config = FcsConfig::withIpWhitelist();
$fcsapi = new FcsApi($config);
```

### Method 4: Token-Based Authentication (Secure for Frontend)
Generate secure tokens on backend, use on frontend without exposing API key:
```php
use FcsApi\FcsConfig;

// Backend: Generate token
$config = FcsConfig::withToken('YOUR_API_KEY', 'YOUR_PUBLIC_KEY', 3600);
$fcsapi = new FcsApi($config);
$tokenData = $fcsapi->generateToken();
// Returns: ['_token' => '...', '_expiry' => 1234567890, '_public_key' => '...']

// Send $tokenData to frontend for secure API calls
```

**Token Expiry Options:**
| Seconds | Duration |
|---------|----------|
| 300 | 5 minutes |
| 900 | 15 minutes |
| 1800 | 30 minutes |
| 3600 | 1 hour |
| 86400 | 24 hours |

## API Reference

### Forex API

```php
// ==================== Symbol List ====================
$fcsapi->forex->getSymbolsList();                    // All symbols
$fcsapi->forex->getSymbolsList('forex');             // Forex only
$fcsapi->forex->getSymbolsList('commodity');         // Commodities only

// ==================== Latest Prices ====================
$fcsapi->forex->getLatestPrice('EURUSD');
$fcsapi->forex->getLatestPrice('EURUSD,GBPUSD,USDJPY');
$fcsapi->forex->getLatestPrice('EURUSD', '1D', null, true);  // with profile
$fcsapi->forex->getAllPrices('FX');                  // All from exchange

// ==================== Commodities ====================
$fcsapi->forex->getCommodities();                    // All commodities
$fcsapi->forex->getCommodities('XAUUSD');           // Gold
$fcsapi->forex->getCommoditySymbols();              // Commodity symbols list

// ==================== Currency Converter ====================
$fcsapi->forex->convert('EUR', 'USD', 100);          // Convert 100 EUR to USD

// ==================== Base Currency ====================
$fcsapi->forex->getBasePrices('USD');                // USD to all currencies

// ==================== Cross Rates ====================
$fcsapi->forex->getCrossRates('USD', 'forex', '1D');

// ==================== Historical Data ====================
$fcsapi->forex->getHistory('EURUSD');
$fcsapi->forex->getHistory('EURUSD', '1D', 500);
$fcsapi->forex->getHistory('EURUSD', '1h', 300, '2025-01-01', '2025-01-31');
$fcsapi->forex->getHistory('EURUSD', '1D', 300, null, null, 2);  // Page 2

// ==================== Profile ====================
$fcsapi->forex->getProfile('EUR');
$fcsapi->forex->getProfile('EUR,USD,GBP');

// ==================== Exchanges ====================
$fcsapi->forex->getExchanges();

// ==================== Technical Analysis ====================
$fcsapi->forex->getMovingAverages('EURUSD', '1D');   // EMA & SMA
$fcsapi->forex->getIndicators('EURUSD', '1D');       // RSI, MACD, Stochastic, etc.
$fcsapi->forex->getPivotPoints('EURUSD', '1D');      // Pivot Points

// ==================== Performance ====================
$fcsapi->forex->getPerformance('EURUSD');            // Highs, lows, volatility

// ==================== Economy Calendar ====================
$fcsapi->forex->getEconomyCalendar();
$fcsapi->forex->getEconomyCalendar('US', '2025-01-01', '2025-01-31');

// ==================== Top Movers ====================
$fcsapi->forex->getTopGainers();
$fcsapi->forex->getTopLosers();
$fcsapi->forex->getMostActive();

// ==================== Search ====================
$fcsapi->forex->search('EUR');

// ==================== Advanced Query ====================
$fcsapi->forex->advanced([
    'type' => 'forex',
    'period' => '1D',
    'sort_by' => 'active.chp_desc',
    'per_page' => 50,
    'merge' => 'latest,profile,tech'
]);
```

### Crypto API

```php
// ==================== Symbol List ====================
$fcsapi->crypto->getSymbolsList();                    // All crypto
$fcsapi->crypto->getSymbolsList('crypto', 'binance'); // Binance only
$fcsapi->crypto->getCoinsList();                      // Coins with market cap

// ==================== Latest Prices ====================
$fcsapi->crypto->getLatestPrice('BTCUSDT');
$fcsapi->crypto->getLatestPrice('BINANCE:BTCUSDT,BINANCE:ETHUSDT');
$fcsapi->crypto->getAllPrices('binance');

// ==================== Coin Data (Market Cap, Rank, Supply) ====================
$fcsapi->crypto->getCoinData();                       // Top coins with full data
$fcsapi->crypto->getTopByMarketCap(100);             // Top 100 by market cap
$fcsapi->crypto->getTopByRank(50);                   // Top 50 by rank

// ==================== Crypto Converter ====================
$fcsapi->crypto->convert('BTC', 'USD', 1);           // 1 BTC to USD
$fcsapi->crypto->convert('ETH', 'BTC', 10);          // 10 ETH to BTC

// ==================== Base Currency ====================
$fcsapi->crypto->getBasePrices('BTC');               // BTC to all
$fcsapi->crypto->getBasePrices('USD');               // USD to all cryptos

// ==================== Cross Rates ====================
$fcsapi->crypto->getCrossRates('USD', 'crypto', '1D');

// ==================== Historical Data ====================
$fcsapi->crypto->getHistory('BINANCE:BTCUSDT');
$fcsapi->crypto->getHistory('BTCUSDT', '1D', 500);

// ==================== Profile ====================
$fcsapi->crypto->getProfile('BTC');
$fcsapi->crypto->getProfile('BTC,ETH,SOL');

// ==================== Exchanges ====================
$fcsapi->crypto->getExchanges();

// ==================== Technical Analysis ====================
$fcsapi->crypto->getMovingAverages('BINANCE:BTCUSDT', '1D');
$fcsapi->crypto->getIndicators('BINANCE:BTCUSDT', '1D');
$fcsapi->crypto->getPivotPoints('BINANCE:BTCUSDT', '1D');

// ==================== Performance ====================
$fcsapi->crypto->getPerformance('BINANCE:BTCUSDT');

// ==================== Top Movers ====================
$fcsapi->crypto->getTopGainers();
$fcsapi->crypto->getTopGainers('binance', 50);
$fcsapi->crypto->getTopLosers();
$fcsapi->crypto->getHighestVolume();

// ==================== Search ====================
$fcsapi->crypto->search('bitcoin');
```

### Stock API

```php
// ==================== Symbol List ====================
$fcsapi->stock->getSymbolsList();                     // All stocks
$fcsapi->stock->getSymbolsList('NASDAQ');            // NASDAQ only
$fcsapi->stock->getSymbolsList(null, 'united-states'); // US stocks
$fcsapi->stock->getSymbolsList(null, null, 'technology'); // Tech sector

// ==================== Indices ====================
$fcsapi->stock->getIndicesList('united-states');     // US indices
$fcsapi->stock->getIndicesLatest();                  // All indices prices
$fcsapi->stock->getIndicesLatest('NASDAQ:NDX,SP:SPX'); // Specific indices

// ==================== Latest Prices ====================
$fcsapi->stock->getLatestPrice('AAPL');
$fcsapi->stock->getLatestPrice('NASDAQ:AAPL,NASDAQ:GOOGL');
$fcsapi->stock->getAllPrices('NASDAQ');
$fcsapi->stock->getLatestByCountry('united-states', 'technology');
$fcsapi->stock->getLatestByIndices('NASDAQ:NDX');    // Stocks in NASDAQ 100

// ==================== Historical Data ====================
$fcsapi->stock->getHistory('NASDAQ:AAPL');
$fcsapi->stock->getHistory('AAPL', '1D', 500);

// ==================== Profile ====================
$fcsapi->stock->getProfile('AAPL');
$fcsapi->stock->getProfile('NASDAQ:AAPL,NASDAQ:GOOGL');

// ==================== Exchanges ====================
$fcsapi->stock->getExchanges();

// ==================== Financial Data ====================
$fcsapi->stock->getEarnings('NASDAQ:AAPL');          // EPS, Revenue
$fcsapi->stock->getEarnings('NASDAQ:AAPL', 'annual'); // Annual only
$fcsapi->stock->getRevenue('NASDAQ:AAPL');           // Revenue segments
$fcsapi->stock->getFinancials('NASDAQ:AAPL', 'income'); // Income statement
$fcsapi->stock->getFinancials('NASDAQ:AAPL', 'balance'); // Balance sheet
$fcsapi->stock->getDividends('NASDAQ:AAPL');         // Dividend history

// ==================== Technical Analysis ====================
$fcsapi->stock->getMovingAverages('NASDAQ:AAPL', '1D');
$fcsapi->stock->getIndicators('NASDAQ:AAPL', '1D');
$fcsapi->stock->getPivotPoints('NASDAQ:AAPL', '1D');

// ==================== Performance ====================
$fcsapi->stock->getPerformance('NASDAQ:AAPL');

// ==================== Top Movers ====================
$fcsapi->stock->getTopGainers();
$fcsapi->stock->getTopGainers('NASDAQ', 50);
$fcsapi->stock->getTopLosers();
$fcsapi->stock->getMostActive();

// ==================== Search & Filter ====================
$fcsapi->stock->search('Apple');
$fcsapi->stock->getBySector('technology');
$fcsapi->stock->getByCountry('united-states');

// ==================== Advanced Query ====================
$fcsapi->stock->advanced([
    'exchange' => 'NASDAQ',
    'sector' => 'technology',
    'period' => '1D',
    'sort_by' => 'active.chp_desc',
    'per_page' => 50,
    'merge' => 'latest,profile'
]);
```

## Response Handling

```php
$response = $fcsapi->forex->getLatestPrice('EURUSD');

// Check if successful
if ($fcsapi->isSuccess()) {
    $data = $response['response'];
    print_r($data);
} else {
    echo "Error: " . $fcsapi->getError();
}

// Get last response info
$lastResponse = $fcsapi->getLastResponse();

// Get response data only
$data = $fcsapi->getResponseData();
```

## Time Periods

Available timeframes for price data:

| Period | Description |
|--------|-------------|
| `1` or `1m` | 1 minute |
| `5` or `5m` | 5 minutes |
| `15` or `15m` | 15 minutes |
| `30` or `30m` | 30 minutes |
| `1h` or `60` | 1 hour |
| `4h` or `240` | 4 hours |
| `1D` | 1 day |
| `1W` | 1 week |
| `1M` | 1 month |

## Examples

### Forex Example
```php
<?php
require 'vendor/autoload.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

// Get EUR/USD latest price
$response = $fcsapi->forex->getLatestPrice('EURUSD');
if ($fcsapi->isSuccess()) {
    foreach ($response['response'] as $item) {
        echo "Symbol: " . $item['ticker'] . "\n";
        echo "Price: " . $item['active']['c'] . "\n";
        echo "Change: " . $item['active']['chp'] . "%\n";
    }
}

// Convert 1000 EUR to USD
$conversion = $fcsapi->forex->convert('EUR', 'USD', 1000);
if ($fcsapi->isSuccess()) {
    echo "1000 EUR = " . $conversion['response']['total'] . " USD\n";
}
```

### Crypto Example
```php
<?php
require 'vendor/autoload.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

// Get Bitcoin price from Binance
$response = $fcsapi->crypto->getLatestPrice('BINANCE:BTCUSDT');
if ($fcsapi->isSuccess()) {
    $btc = $response['response'][0];
    echo "Bitcoin: $" . number_format($btc['active']['c'], 2) . "\n";
}

// Get top 100 coins by market cap
$coins = $fcsapi->crypto->getTopByMarketCap(100);
if ($fcsapi->isSuccess()) {
    foreach ($coins['response']['data'] as $coin) {
        echo $coin['ticker'] . ": Rank #" . $coin['rank'] . "\n";
    }
}
```

### Stock Example
```php
<?php
require 'vendor/autoload.php';

use FcsApi\FcsApi;

$fcsapi = new FcsApi();

// Get Apple stock price
$response = $fcsapi->stock->getLatestPrice('NASDAQ:AAPL');
if ($fcsapi->isSuccess()) {
    $aapl = $response['response'][0];
    echo "Apple: $" . $aapl['active']['c'] . "\n";
}

// Get Apple earnings data
$earnings = $fcsapi->stock->getEarnings('NASDAQ:AAPL');
if ($fcsapi->isSuccess()) {
    echo "EPS Data Available\n";
}

// Get US market indices
$indices = $fcsapi->stock->getIndicesLatest(null, 'united-states');
if ($fcsapi->isSuccess()) {
    foreach ($indices['response'] as $index) {
        echo $index['ticker'] . ": " . $index['active']['c'] . "\n";
    }
}
```

## Get API Key

1. Visit [FCS API](https://fcsapi.com)
2. Sign up for a free account
3. Get your API key from the dashboard

## Documentation

For complete API documentation, visit:
- [Forex API Documentation](https://fcsapi.com/document/forex-api)
- [Crypto API Documentation](https://fcsapi.com/document/crypto-api)
- [Stock API Documentation](https://fcsapi.com/document/stock-api)

## Support

- Email: support@fcsapi.com
- Website: [fcsapi.com](https://fcsapi.com)

## License

MIT License - see [LICENSE](LICENSE) file for details.
