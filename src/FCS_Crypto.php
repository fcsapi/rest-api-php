<?php
/**
 * FCS API - Crypto Module
 *
 * @package FcsApi
 * @author FCS API <support@fcsapi.com>
 */

namespace FcsApi;

class Crypto
{
    private FcsApi $api;
    private string $base = 'crypto/';

    public function __construct(FcsApi $api)
    {
        $this->api = $api;
    }

    // ==================== Symbol List ====================

    /**
     * Get list of all crypto symbols
     *
     * @param string|null $type Filter: crypto, coin, futures, dex, dominance
     * @param string|null $subType Filter: spot, swap, index
     * @param string|null $exchange Filter by exchange: BINANCE, COINBASE
     * @return array|null
     */
    public function getSymbolsList(?string $type = 'crypto', ?string $subType = null, ?string $exchange = null): ?array
    {
        $params = [];
        if ($type) $params['type'] = $type;
        if ($subType) $params['sub_type'] = $subType;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'list', $params);
    }

    /**
     * Get list of all coins (with market cap, rank, supply data)
     *
     * @return array|null
     */
    public function getCoinsList(): ?array
    {
        return $this->getSymbolsList('coin');
    }

    // ==================== Latest Prices ====================

    /**
     * Get latest prices
     *
     * @param string $symbol Symbol(s): BTCUSDT,ETHUSDT or BINANCE:BTCUSDT
     * @param string $period Time period: 1m,5m,15m,30m,1h,4h,1D,1W,1M
     * @param string|null $type crypto or coin
     * @param string|null $exchange Exchange name (BINANCE, COINBASE)
     * @param bool $getProfile Include profile info
     * @return array|null
     */
    public function getLatestPrice(string $symbol, string $period = '1D', ?string $type = null, ?string $exchange = null, bool $getProfile = false): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period
        ];
        if ($type) $params['type'] = $type;
        if ($exchange) $params['exchange'] = $exchange;
        if ($getProfile) $params['get_profile'] = 1;

        return $this->api->request($this->base . 'latest', $params);
    }

    /**
     * Get all latest prices by exchange
     *
     * @param string $exchange Exchange: BINANCE, COINBASE, KRAKEN
     * @param string $period Time period
     * @param string|null $type crypto or coin
     * @return array|null
     */
    public function getAllPrices(string $exchange, string $period = '1D', ?string $type = null): ?array
    {
        $params = [
            'exchange' => $exchange,
            'period' => $period
        ];
        if ($type) $params['type'] = $type;

        return $this->api->request($this->base . 'latest', $params);
    }

    // ==================== Coin Data (Rank, Market Cap, Supply) ====================

    /**
     * Get coin data with rank, market cap, supply, performance
     * Note: Only works with type=coin (BTCUSD, ETHUSD, etc.)
     *
     * @param string|null $symbol Coin symbol: BTCUSD, ETHUSD (optional)
     * @param int $limit Number of results
     * @param string $sortBy Sort by: rank_asc, market_cap_desc, circulating_supply_desc
     * @return array|null
     */
    public function getCoinData(?string $symbol = null, int $limit = 100, string $sortBy = 'perf.rank_asc'): ?array
    {
        $params = [
            'type' => 'coin',
            'sort_by' => $sortBy,
            'per_page' => $limit,
            'merge' => 'latest,perf'
        ];
        if ($symbol) $params['symbol'] = $symbol;

        return $this->api->request($this->base . 'advance', $params);
    }

    /**
     * Get top coins by market cap
     *
     * @param int $limit Number of results
     * @return array|null
     */
    public function getTopByMarketCap(int $limit = 100): ?array
    {
        return $this->getCoinData(null, $limit, 'perf.market_cap_desc');
    }

    /**
     * Get top coins by rank
     *
     * @param int $limit Number of results
     * @return array|null
     */
    public function getTopByRank(int $limit = 100): ?array
    {
        return $this->getCoinData(null, $limit, 'perf.rank_asc');
    }

    // ==================== Crypto Converter ====================

    /**
     * Crypto converter (crypto to fiat or crypto to crypto)
     *
     * @param string $pair1 From: BTC, ETH
     * @param string $pair2 To: USD, EUR, BTC
     * @param float $amount Amount to convert
     * @return array|null
     */
    public function convert(string $pair1, string $pair2, float $amount = 1): ?array
    {
        return $this->api->request($this->base . 'converter', [
            'pair1' => $pair1,
            'pair2' => $pair2,
            'amount' => $amount
        ]);
    }

    // ==================== Base Currency ====================

    /**
     * Get base currency prices (USD to all cryptos, BTC to all)
     * Symbol accepts only single token: BTC, ETH, USD (not BTCUSDT)
     *
     * @param string $symbol Single currency: BTC, ETH, USD
     * @param string|null $exchange Exchange filter
     * @param bool $fallback If pair not found, fetch from other exchanges
     * @return array|null
     */
    public function getBasePrices(string $symbol, ?string $exchange = null, bool $fallback = false): ?array
    {
        $params = [
            'symbol' => $symbol
        ];
        if ($exchange) $params['exchange'] = $exchange;
        if ($fallback) $params['fallback'] = 1;

        return $this->api->request($this->base . 'base_latest', $params);
    }

    // ==================== Cross Currency ====================

    /**
     * Get cross currency rates with OHLC data
     * Returns all pairs of base currency (USD -> USDBTC, USDETH, etc.)
     *
     * @param string $symbol Single currency: USD, BTC, ETH
     * @param string $type crypto or forex
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @param bool $crossrates Return pairwise cross rates between multiple symbols
     * @param bool $fallback If not found, fetch from other exchanges
     * @return array|null
     */
    public function getCrossRates(string $symbol, ?string $exchange = null, string $type = 'crypto', string $period = '1D', bool $crossrates = false, bool $fallback = false): ?array
    {
        $params = [
            'symbol' => $symbol,
            'type' => $type,
            'period' => $period
        ];
        if ($exchange) $params['exchange'] = $exchange;
        if ($crossrates) $params['crossrates'] = 1;
        if ($fallback) $params['fallback'] = 1;

        return $this->api->request($this->base . 'cross', $params);
    }

    // ==================== Historical Data ====================

    /**
     * Get historical prices (OHLCV candles)
     *
     * @param string $symbol Single symbol: BINANCE:BTCUSDT or BTCUSDT
     * @param string $period Time period: 1m,5m,15m,1h,1D
     * @param int $length Number of candles (max 10000)
     * @param string|null $from Start date (YYYY-MM-DD)
     * @param string|null $to End date (YYYY-MM-DD)
     * @param int $page Page number for pagination
     * @param bool $isChart Return chart-friendly format [timestamp,o,h,l,c,v]
     * @return array|null
     */
    public function getHistory(string $symbol, string $period = '1D', int $length = 300, ?string $from = null, ?string $to = null, int $page = 1, bool $isChart = false): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period,
            'length' => $length,
            'page' => $page
        ];
        if ($from) $params['from'] = $from;
        if ($to) $params['to'] = $to;
        if ($isChart) $params['is_chart'] = 1;

        return $this->api->request($this->base . 'history', $params);
    }

    // ==================== Profile ====================

    /**
     * Get coin profile details (name, website, social links, etc.)
     *
     * @param string $symbol Coin symbol: BTC,ETH,SOL (not pairs)
     * @return array|null
     */
    public function getProfile(string $symbol): ?array
    {
        return $this->api->request($this->base . 'profile', [
            'symbol' => $symbol
        ]);
    }

    // ==================== Exchanges ====================

    /**
     * Get available exchanges
     *
     * @param string|null $type crypto, coin, futures, dex
     * @param string|null $subType spot, swap
     * @return array|null
     */
    public function getExchanges(?string $type = null, ?string $subType = null): ?array
    {
        $params = [];
        if ($type) $params['type'] = $type;
        if ($subType) $params['sub_type'] = $subType;

        return $this->api->request($this->base . 'exchanges', $params);
    }

    // ==================== Advanced Query ====================

    /**
     * Advanced query with filters, sorting, pagination, merging
     *
     * @param array $params Query parameters:
     *   - type: crypto, coin, futures, dex
     *   - symbol: BTCUSDT,ETHUSDT
     *   - exchange: BINANCE,COINBASE
     *   - period: 1D
     *   - merge: latest,perf,tech,profile,meta
     *   - sort_by: active.chp_desc, rank_asc, market_cap_desc
     *   - filters: {"active.c_gt":50000}
     *   - per_page: 200
     *   - page: 1
     * @return array|null
     */
    public function advanced(array $params): ?array
    {
        return $this->api->request($this->base . 'advance', $params);
    }

    // ==================== Technical Analysis ====================

    /**
     * Get Moving Averages (EMA & SMA)
     *
     * @param string $symbol Symbol(s): BTCUSDT or BINANCE:BTCUSDT
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getMovingAverages(string $symbol, string $period = '1D', ?string $exchange = null): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'ma_avg', $params);
    }

    /**
     * Get Technical Indicators (RSI, MACD, Stochastic, ADX, ATR, etc.)
     *
     * @param string $symbol Symbol(s): BTCUSDT
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getIndicators(string $symbol, string $period = '1D', ?string $exchange = null): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'indicators', $params);
    }

    /**
     * Get Pivot Points (Classic, Fibonacci, Camarilla, Woodie, Demark)
     *
     * @param string $symbol Symbol(s): BTCUSDT
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getPivotPoints(string $symbol, string $period = '1D', ?string $exchange = null): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'pivot_points', $params);
    }

    // ==================== Performance ====================

    /**
     * Get Performance Data (historical highs/lows, percentage changes, volatility)
     *
     * @param string $symbol Symbol(s): BTCUSDT
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getPerformance(string $symbol, ?string $exchange = null): ?array
    {
        $params = ['symbol' => $symbol];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'performance', $params);
    }

    // ==================== Top Movers ====================

    /**
     * Get top gainers
     *
     * @param string|null $exchange Exchange filter: BINANCE, COINBASE
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string $type crypto or coin
     * @return array|null
     */
    public function getTopGainers(?string $exchange = null, int $limit = 20, string $period = '1D', string $type = 'crypto'): ?array
    {
        return $this->getSortedData('active.chp', 'desc', $limit, $type, $exchange, $period);
    }

    /**
     * Get top losers
     *
     * @param string|null $exchange Exchange filter
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string $type crypto or coin
     * @return array|null
     */
    public function getTopLosers(?string $exchange = null, int $limit = 20, string $period = '1D', string $type = 'crypto'): ?array
    {
        return $this->getSortedData('active.chp', 'asc', $limit, $type, $exchange, $period);
    }

    /**
     * Get highest volume coins
     *
     * @param string|null $exchange Exchange filter
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string $type crypto or coin
     * @return array|null
     */
    public function getHighestVolume(?string $exchange = null, int $limit = 20, string $period = '1D', string $type = 'crypto'): ?array
    {
        return $this->getSortedData('active.v', 'desc', $limit, $type, $exchange, $period);
    }

    // ==================== Custom Sorting ====================

    /**
     * Get data with custom sorting
     * User can specify any column and sort direction
     *
     * @param string $sortColumn Column to sort: active.c, active.chp, active.v, active.h, active.l, rank, market_cap
     * @param string $sortDirection Sort direction: asc or desc
     * @param int $limit Number of results
     * @param string|null $type crypto, coin, futures, dex
     * @param string|null $exchange Exchange filter: BINANCE, COINBASE
     * @param string $period Time period
     * @return array|null
     */
    public function getSortedData(string $sortColumn, string $sortDirection = 'desc', int $limit = 20, ?string $type = 'crypto', ?string $exchange = null, string $period = '1D'): ?array
    {
        $params = [
            'period' => $period,
            'sort_by' => $sortColumn . '_' . $sortDirection,
            'per_page' => $limit,
            'merge' => 'latest'
        ];
        if ($type) $params['type'] = $type;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'advance', $params);
    }

    // ==================== Search ====================

    /**
     * Search coins/tokens
     *
     * @param string $query Search term (BTC, ethereum, doge)
     * @param string|null $type crypto, coin, futures, dex
     * @return array|null
     */
    public function search(string $query, ?string $type = null): ?array
    {
        $params = ['search' => $query];
        if ($type) $params['type'] = $type;

        return $this->api->request($this->base . 'list', $params);
    }

    // ==================== Multiple/Parallel Requests ====================

    /**
     * Execute multiple API requests in parallel
     *
     * @param array $urls Array of API endpoints
     * @param string|null $base Common URL base
     * @return array|null
     */
    public function multiUrl(array $urls, ?string $base = null): ?array
    {
        $params = ['url' => $urls];
        if ($base) $params['base'] = $base;

        return $this->api->request($this->base . 'multi_url', $params);
    }
}
