<?php
/**
 * FCS API - Forex Module
 *
 * @package FcsApi
 * @author FCS API <support@fcsapi.com>
 */

namespace FcsApi;

class Forex
{
    private FcsApi $api;
    private string $base = 'forex/';

    public function __construct(FcsApi $api)
    {
        $this->api = $api;
    }

    // ==================== Symbol List ====================

    /**
     * Get list of all forex symbols
     *
     * @param string|null $type Filter by type: forex, commodity
     * @param string|null $subType Filter: spot, synthetic
     * @param string|null $exchange Filter by exchange: FX, ONA, SFO, FCM
     * @return array|null
     */
    public function getSymbolsList(?string $type = null, ?string $subType = null, ?string $exchange = null): ?array
    {
        $params = [];
        if ($type) $params['type'] = $type;
        if ($subType) $params['sub_type'] = $subType;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'list', $params);
    }

    // ==================== Latest Prices ====================

    /**
     * Get latest prices for symbols
     *
     * @param string $symbol Symbol(s) comma-separated: EURUSD,GBPUSD or FX:EURUSD
     * @param string $period Time period: 1m,5m,15m,30m,1h,4h,1D,1W,1M
     * @param string|null $type forex or commodity
     * @param string|null $exchange Exchange name: FX, ONA, SFO
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
     * @param string $exchange Exchange name: FX, ONA, SFO
     * @param string $period Time period
     * @param string|null $type forex or commodity
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

    // ==================== Commodities ====================

    /**
     * Get commodity prices (Gold, Silver, Oil, etc.)
     *
     * @param string|null $symbol Commodity symbol: XAUUSD, XAGUSD, USOIL, BRENT, NGAS
     * @param string $period Time period
     * @return array|null
     */
    public function getCommodities(?string $symbol = null, string $period = '1D'): ?array
    {
        $params = ['type' => 'commodity', 'period' => $period];
        if ($symbol) $params['symbol'] = $symbol;

        return $this->api->request($this->base . 'latest', $params);
    }

    /**
     * Get commodity symbols list
     *
     * @return array|null
     */
    public function getCommoditySymbols(): ?array
    {
        return $this->getSymbolsList('commodity');
    }

    // ==================== Currency Converter ====================

    /**
     * Currency converter
     *
     * @param string $pair1 Currency From: EUR, USD
     * @param string $pair2 Currency To: USD, GBP
     * @param float $amount Amount to convert
     * @param string|null $type forex or crypto
     * @return array|null
     */
    public function convert(string $pair1, string $pair2, float $amount = 1, ?string $type = null): ?array
    {
        $params = [
            'pair1' => $pair1,
            'pair2' => $pair2,
            'amount' => $amount
        ];
        if ($type) $params['type'] = $type;

        return $this->api->request($this->base . 'converter', $params);
    }

    // ==================== Base Currency ====================

    /**
     * Get base currency prices (USD to all currencies)
     * Symbol accepts only single currency: USD, EUR, JPY (not USDJPY)
     *
     * @param string $symbol Single currency code: USD, EUR, JPY
     * @param string $type forex or crypto
     * @param string|null $exchange Exchange filter
     * @param bool $fallback If not found, fetch from other exchanges
     * @return array|null
     */
    public function getBasePrices(string $symbol, string $type = 'forex', ?string $exchange = null, bool $fallback = false): ?array
    {
        $params = [
            'symbol' => $symbol,
            'type' => $type
        ];
        if ($exchange) $params['exchange'] = $exchange;
        if ($fallback) $params['fallback'] = 1;

        return $this->api->request($this->base . 'base_latest', $params);
    }

    // ==================== Cross Currency ====================

    /**
     * Get cross currency rates with OHLC data
     * Returns all pairs of base currency (USD -> USDEUR, USDGBP, USDJPY, etc.)
     *
     * @param string $symbol Single currency: USD, EUR, JPY
     * @param string $type forex or crypto
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @param bool $crossrates Return pairwise cross rates between multiple symbols
     * @param bool $fallback If not found, fetch from other exchanges
     * @return array|null
     */
    public function getCrossRates(string $symbol, ?string $exchange = null, string $type = 'forex', string $period = '1D', bool $crossrates = false, bool $fallback = false): ?array
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
     * @param string $symbol Single symbol: EURUSD or FX:EURUSD
     * @param string $period Time period: 1m,5m,15m,1h,1D
     * @param int $length Number of candles (max 10000)
     * @param string|null $from Start date (YYYY-MM-DD or unix)
     * @param string|null $to End date (YYYY-MM-DD or unix)
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
     * Get currency profile details (name, country, bank, etc.)
     *
     * @param string $symbol Currency codes: EUR,USD,GBP (not pairs)
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
     * Get available exchanges/data sources
     *
     * @param string|null $type forex or commodity
     * @param string|null $subType spot, synthetic
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
     *   - type: forex, commodity
     *   - symbol: EURUSD,GBPUSD
     *   - exchange: FX,ONA,SFO
     *   - period: 1D
     *   - merge: latest,perf,tech,profile,meta
     *   - sort_by: active.chp_desc, active.v_desc
     *   - filters: {"active.c_gt":1.1}
     *   - per_page: 200 (max 5000)
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
     * @param string $symbol Symbol(s): EURUSD or FX:EURUSD
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
     * @param string $symbol Symbol(s): EURUSD
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
     * @param string $symbol Symbol(s): EURUSD
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
     * @param string $symbol Symbol(s): EURUSD
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getPerformance(string $symbol, ?string $exchange = null): ?array
    {
        $params = ['symbol' => $symbol];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'performance', $params);
    }

    // ==================== Economy Calendar ====================

    /**
     * Get Economic Calendar Events
     *
     * @param string|null $symbol Filter by currency: USD, EUR, GBP
     * @param string|null $country Country filter: US, GB, DE, JP
     * @param string|null $from Start date (YYYY-MM-DD)
     * @param string|null $to End date (YYYY-MM-DD)
     * @return array|null
     */
    public function getEconomyCalendar(?string $symbol = null, ?string $country = null, ?string $from = null, ?string $to = null): ?array
    {
        $params = [];
        if ($symbol) $params['symbol'] = $symbol;
        if ($country) $params['country'] = $country;
        if ($from) $params['from'] = $from;
        if ($to) $params['to'] = $to;

        return $this->api->request($this->base . 'economy_cal', $params);
    }

    // ==================== Top Movers ====================

    /**
     * Get top gainers
     *
     * @param string $type forex or commodity
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getTopGainers(string $type = 'forex', int $limit = 20, string $period = '1D', ?string $exchange = null): ?array
    {
        return $this->getSortedData('active.chp', 'desc', $limit, $type, $exchange, $period);
    }

    /**
     * Get top losers
     *
     * @param string $type forex or commodity
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getTopLosers(string $type = 'forex', int $limit = 20, string $period = '1D', ?string $exchange = null): ?array
    {
        return $this->getSortedData('active.chp', 'asc', $limit, $type, $exchange, $period);
    }

    /**
     * Get most active by volume
     *
     * @param string $type forex or commodity
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getMostActive(string $type = 'forex', int $limit = 20, string $period = '1D', ?string $exchange = null): ?array
    {
        return $this->getSortedData('active.v', 'desc', $limit, $type, $exchange, $period);
    }

    // ==================== Custom Sorting ====================

    /**
     * Get data with custom sorting
     * User can specify any column and sort direction
     *
     * @param string $sortColumn Column to sort: active.c, active.chp, active.v, active.h, active.l
     * @param string $sortDirection Sort direction: asc or desc
     * @param int $limit Number of results
     * @param string|null $type forex or commodity
     * @param string|null $exchange Exchange filter: FX, ONA, SFO
     * @param string $period Time period
     * @return array|null
     */
    public function getSortedData(string $sortColumn, string $sortDirection = 'desc', int $limit = 20, ?string $type = 'forex', ?string $exchange = null, string $period = '1D'): ?array
    {
        $params = [
            'period' => $period,
            'sort_by' => $sortColumn . '_' . $sortDirection,
            'per_page' => $limit,
            'merge' => 'latest'
        ];
        if ($type) $params['type'] = $type;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->advanced($params);
    }

    // ==================== Search ====================

    /**
     * Search symbols
     *
     * @param string $query Search term (EUR, USD, gold)
     * @param string|null $type forex or commodity
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function search(string $query, ?string $type = null, ?string $exchange = null): ?array
    {
        $params = ['search' => $query];
        if ($type) $params['type'] = $type;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'search', $params);
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
