<?php
/**
 * FCS API - Stock Module
 *
 * @package FcsApi
 * @author FCS API <support@fcsapi.com>
 */

namespace FcsApi;

class Stock
{
    private FcsApi $api;
    private string $base = 'stock/';

    public function __construct(FcsApi $api)
    {
        $this->api = $api;
    }

    // ==================== Symbol/Stock List ====================

    /**
     * Get list of all stock symbols
     *
     * @param string|null $exchange Filter by exchange: NASDAQ, NYSE, BSE
     * @param string|null $country Filter by country: united-states, japan, india
     * @param string|null $sector Filter by sector: technology, finance, energy
     * @param string|null $indices Filter by indices: DJ:DJI, NASDAQ:IXIC
     * @return array|null
     */
    public function getSymbolsList(?string $exchange = null, ?string $country = null, ?string $sector = null, ?string $indices = null): ?array
    {
        $params = [];
        if ($exchange) $params['exchange'] = $exchange;
        if ($country) $params['country'] = $country;
        if ($sector) $params['sector'] = $sector;
        if ($indices) $params['indices'] = $indices;

        return $this->api->request($this->base . 'list', $params);
    }

    // ==================== Indices ====================

    /**
     * Get list of market indices by country
     *
     * @param string|null $country Country name: united-states, japan
     * @param string|null $exchange Exchange filter: nasdaq, nyse
     * @return array|null
     */
    public function getIndicesList(?string $country = null, ?string $exchange = null): ?array
    {
        $params = [];
        if ($country) $params['country'] = $country;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'indices', $params);
    }

    /**
     * Get latest index prices
     *
     * @param string|null $symbol Index symbol(s): NASDAQ:NDX, SP:SPX
     * @param string|null $country Country filter
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getIndicesLatest(?string $symbol = null, ?string $country = null, ?string $exchange = null): ?array
    {
        $params = [];
        if ($symbol) $params['symbol'] = $symbol;
        if ($country) $params['country'] = $country;
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'indices_latest', $params);
    }

    // ==================== Latest Prices ====================

    /**
     * Get latest stock prices
     *
     * @param string $symbol Symbol(s): AAPL,GOOGL or NASDAQ:AAPL
     * @param string $period Time period: 1m,5m,15m,30m,1h,4h,1D,1W,1M
     * @param string|null $exchange Exchange name
     * @param bool $getProfile Include profile info
     * @return array|null
     */
    public function getLatestPrice(string $symbol, string $period = '1D', ?string $exchange = null, bool $getProfile = false): ?array
    {
        $params = [
            'symbol' => $symbol,
            'period' => $period,
            'get_profile' => $getProfile ? 1 : 0
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->api->request($this->base . 'latest', $params);
    }

    /**
     * Get all latest prices by exchange
     *
     * @param string $exchange Exchange: NASDAQ, NYSE, LSE
     * @param string $period Time period
     * @return array|null
     */
    public function getAllPrices(string $exchange, string $period = '1D'): ?array
    {
        return $this->api->request($this->base . 'latest', [
            'exchange' => $exchange,
            'period' => $period
        ]);
    }

    /**
     * Get latest prices by country and sector
     *
     * @param string $country Country: united-states, japan
     * @param string|null $sector Sector: technology, finance
     * @param string $period Time period
     * @return array|null
     */
    public function getLatestByCountry(string $country, ?string $sector = null, string $period = '1D'): ?array
    {
        $params = [
            'country' => $country,
            'period' => $period
        ];
        if ($sector) $params['sector'] = $sector;

        return $this->api->request($this->base . 'latest', $params);
    }

    /**
     * Get latest prices by indices
     *
     * @param string $indices Indices IDs: NASDAQ:NDX, SP:SPX
     * @param string $period Time period
     * @return array|null
     */
    public function getLatestByIndices(string $indices, string $period = '1D'): ?array
    {
        return $this->api->request($this->base . 'latest', [
            'indices' => $indices,
            'period' => $period
        ]);
    }

    // ==================== Historical Data ====================

    /**
     * Get historical prices (works for stocks and indices)
     *
     * @param string $symbol Single symbol: AAPL or NASDAQ:AAPL
     * @param string $period Time period
     * @param int $length Number of candles (max 10000)
     * @param string|null $from Start date
     * @param string|null $to End date
     * @param int $page Page number for pagination
     * @param bool $isChart Return chart-friendly format
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
     * Get stock profile/company details
     *
     * @param string $symbol Stock symbol: AAPL,GOOGL or NASDAQ:AAPL
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
     * @param string|null $type Filter: stock, all_stock
     * @param string|null $subType Filter: equity, etf
     * @return array|null
     */
    public function getExchanges(?string $type = null, ?string $subType = null): ?array
    {
        $params = [];
        if ($type) $params['type'] = $type;
        if ($subType) $params['sub_type'] = $subType;

        return $this->api->request($this->base . 'exchanges', $params);
    }

    // ==================== Financial Data ====================

    /**
     * Get earnings data (EPS, Revenue)
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL (can be multiple comma-separated)
     * @param string $duration Filter: annual, interim, both
     * @return array|null
     */
    public function getEarnings(string $symbol, string $duration = 'both'): ?array
    {
        return $this->api->request($this->base . 'earnings', [
            'symbol' => $symbol,
            'duration' => $duration
        ]);
    }

    /**
     * Get revenue segmentation data (by business and region)
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL (can be multiple comma-separated)
     * @return array|null
     */
    public function getRevenue(string $symbol): ?array
    {
        return $this->api->request($this->base . 'revenue', [
            'symbol' => $symbol
        ]);
    }

    /**
     * Get dividends data (payment dates, amounts, yield)
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $format Response format: plain (default), inherit (nested array)
     * @return array|null
     */
    public function getDividends(string $symbol, string $format = 'plain'): ?array
    {
        return $this->api->request($this->base . 'dividend', [
            'symbol' => $symbol,
            'format' => $format
        ]);
    }

    /**
     * Get balance sheet data
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $duration annual, interim
     * @param string $format Response format: plain, inherit
     * @return array|null
     */
    public function getBalanceSheet(string $symbol, string $duration = 'annual', string $format = 'plain'): ?array
    {
        return $this->api->request($this->base . 'balance_sheet', [
            'symbol' => $symbol,
            'duration' => $duration,
            'format' => $format
        ]);
    }

    /**
     * Get income statement data
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $duration annual, interim
     * @param string $format Response format: plain, inherit
     * @return array|null
     */
    public function getIncomeStatements(string $symbol, string $duration = 'annual', string $format = 'plain'): ?array
    {
        return $this->api->request($this->base . 'income_statements', [
            'symbol' => $symbol,
            'duration' => $duration,
            'format' => $format
        ]);
    }

    /**
     * Get cash flow statement data
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $duration annual, interim
     * @param string $format Response format: plain, inherit
     * @return array|null
     */
    public function getCashFlow(string $symbol, string $duration = 'annual', string $format = 'plain'): ?array
    {
        return $this->api->request($this->base . 'cash_flow', [
            'symbol' => $symbol,
            'duration' => $duration,
            'format' => $format
        ]);
    }

    /**
     * Get stock statistics and financial ratios
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $duration annual, interim
     * @return array|null
     */
    public function getStatistics(string $symbol, string $duration = 'annual'): ?array
    {
        return $this->api->request($this->base . 'statistics', [
            'symbol' => $symbol,
            'duration' => $duration
        ]);
    }

    /**
     * Get price target forecast from analysts
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @return array|null
     */
    public function getForecast(string $symbol): ?array
    {
        return $this->api->request($this->base . 'forecast', [
            'symbol' => $symbol
        ]);
    }

    /**
     * Get combined financial data (multiple endpoints in one call)
     *
     * @param string $symbol Stock symbol: NASDAQ:AAPL
     * @param string $dataColumn Comma-separated: earnings,revenue,profile,dividends,balance_sheet,income_statements,statistics,cash_flow
     * @param string $duration annual, interim
     * @param string $format Response format: plain, inherit
     * @return array|null
     */
    public function getStockData(string $symbol, string $dataColumn = 'profile,earnings,dividends', string $duration = 'annual', string $format = 'plain'): ?array
    {
        return $this->api->request($this->base . 'stock_data', [
            'symbol' => $symbol,
            'data_column' => $dataColumn,
            'duration' => $duration,
            'format' => $format
        ]);
    }

    // ==================== Technical Analysis ====================

    /**
     * Get Moving Averages (EMA & SMA)
     *
     * @param string $symbol Symbol: NASDAQ:AAPL
     * @param string $period Time period
     * @return array|null
     */
    public function getMovingAverages(string $symbol, string $period = '1D'): ?array
    {
        return $this->api->request($this->base . 'ma_avg', [
            'symbol' => $symbol,
            'period' => $period
        ]);
    }

    /**
     * Get Technical Indicators (RSI, MACD, Stochastic, ADX, ATR, etc.)
     *
     * @param string $symbol Symbol: NASDAQ:AAPL
     * @param string $period Time period
     * @return array|null
     */
    public function getIndicators(string $symbol, string $period = '1D'): ?array
    {
        return $this->api->request($this->base . 'indicators', [
            'symbol' => $symbol,
            'period' => $period
        ]);
    }

    /**
     * Get Pivot Points
     *
     * @param string $symbol Symbol: NASDAQ:AAPL
     * @param string $period Time period
     * @return array|null
     */
    public function getPivotPoints(string $symbol, string $period = '1D'): ?array
    {
        return $this->api->request($this->base . 'pivot_points', [
            'symbol' => $symbol,
            'period' => $period
        ]);
    }

    // ==================== Performance ====================

    /**
     * Get Performance Data (historical highs/lows, percentage changes, volatility)
     *
     * @param string $symbol Symbol: NASDAQ:AAPL
     * @return array|null
     */
    public function getPerformance(string $symbol): ?array
    {
        return $this->api->request($this->base . 'performance', [
            'symbol' => $symbol
        ]);
    }

    // ==================== Advanced Query ====================

    /**
     * Advanced query with filters, sorting, pagination, merging
     *
     * @param array $params Query parameters:
     *   - type: stock, index
     *   - symbol: AAPL,GOOGL
     *   - exchange: NASDAQ,NYSE
     *   - country: united-states
     *   - sector: technology
     *   - period: 1D
     *   - merge: latest,perf,tech,profile,meta
     *   - sort_by: active.chp_desc
     *   - filters: {"active.c_gt":100}
     *   - per_page: 200
     *   - page: 1
     * @return array|null
     */
    public function advanced(array $params): ?array
    {
        return $this->api->request($this->base . 'advance', $params);
    }

    // ==================== Top Movers ====================

    /**
     * Get top gainers
     *
     * @param string|null $exchange Exchange filter: NASDAQ, NYSE
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $country Country filter
     * @return array|null
     */
    public function getTopGainers(?string $exchange = null, int $limit = 20, string $period = '1D', ?string $country = null): ?array
    {
        return $this->getSortedData('active.chp', 'desc', $limit, $exchange, $country, $period);
    }

    /**
     * Get top losers
     *
     * @param string|null $exchange Exchange filter
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $country Country filter
     * @return array|null
     */
    public function getTopLosers(?string $exchange = null, int $limit = 20, string $period = '1D', ?string $country = null): ?array
    {
        return $this->getSortedData('active.chp', 'asc', $limit, $exchange, $country, $period);
    }

    /**
     * Get most active stocks by volume
     *
     * @param string|null $exchange Exchange filter
     * @param int $limit Number of results
     * @param string $period Time period
     * @param string|null $country Country filter
     * @return array|null
     */
    public function getMostActive(?string $exchange = null, int $limit = 20, string $period = '1D', ?string $country = null): ?array
    {
        return $this->getSortedData('active.v', 'desc', $limit, $exchange, $country, $period);
    }

    // ==================== Custom Sorting ====================

    /**
     * Get data with custom sorting
     * User can specify any column and sort direction
     *
     * @param string $sortColumn Column to sort: active.c, active.chp, active.v, active.h, active.l
     * @param string $sortDirection Sort direction: asc or desc
     * @param int $limit Number of results
     * @param string|null $exchange Exchange filter: NASDAQ, NYSE, LSE
     * @param string|null $country Country filter
     * @param string $period Time period
     * @return array|null
     */
    public function getSortedData(string $sortColumn, string $sortDirection = 'desc', int $limit = 20, ?string $exchange = null, ?string $country = null, string $period = '1D'): ?array
    {
        $params = [
            'period' => $period,
            'sort_by' => $sortColumn . '_' . $sortDirection,
            'per_page' => $limit,
            'merge' => 'latest'
        ];
        if ($exchange) $params['exchange'] = $exchange;
        if ($country) $params['country'] = $country;

        return $this->advanced($params);
    }

    // ==================== Search ====================

    /**
     * Search stocks
     *
     * @param string $query Search term
     * @param string|null $exchange Exchange filter
     * @param string|null $country Country filter
     * @return array|null
     */
    public function search(string $query, ?string $exchange = null, ?string $country = null): ?array
    {
        $params = ['search' => $query];
        if ($exchange) $params['exchange'] = $exchange;
        if ($country) $params['country'] = $country;

        return $this->api->request($this->base . 'list', $params);
    }

    // ==================== Filter by Sector/Country ====================

    /**
     * Get stocks by sector
     *
     * @param string $sector Sector: technology, finance, energy, healthcare
     * @param int $limit Number of results
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getBySector(string $sector, int $limit = 100, ?string $exchange = null): ?array
    {
        $params = [
            'sector' => $sector,
            'per_page' => $limit,
            'merge' => 'latest'
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->advanced($params);
    }

    /**
     * Get stocks by country
     *
     * @param string $country Country: united-states, japan, india
     * @param int $limit Number of results
     * @param string|null $exchange Exchange filter
     * @return array|null
     */
    public function getByCountry(string $country, int $limit = 100, ?string $exchange = null): ?array
    {
        $params = [
            'country' => $country,
            'per_page' => $limit,
            'merge' => 'latest'
        ];
        if ($exchange) $params['exchange'] = $exchange;

        return $this->advanced($params);
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
