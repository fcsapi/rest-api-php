<?php
/**
 * FCS API - REST API Client
 *
 * PHP client for Forex, Cryptocurrency, and Stock market data
 *
 * @package FcsApi
 * @author FCS API <support@fcsapi.com>
 * @link https://fcsapi.com
 */

namespace FcsApi;

require_once __DIR__ . '/FcsConfig.php';


class FcsApi
{
    /** @var string API Base URL */
    private const BASE_URL = 'https://api-v4.fcsapi.com/';

    /** @var FcsConfig Configuration */
    private FcsConfig $config;

    /** @var Forex|null Forex API instance */
    private ?Forex $_forex = null;

    /** @var Crypto|null Crypto API instance */
    private ?Crypto $_crypto = null;

    /** @var Stock|null Stock API instance */
    private ?Stock $_stock = null;

    /** @var array Last response info */
    private array $lastResponse = [];

    /**
     * Constructor
     *
     * @param string|FcsConfig|null $config API key string, FcsConfig object, or null to use default config
     */
    public function __construct($config = null)
    {
        if ($config instanceof FcsConfig) {
            $this->config = $config;
        } elseif (is_string($config)) {
            // Backward compatible: accept string API key
            $this->config = FcsConfig::withAccessKey($config);
        } else {
            // Use default config (key from FcsConfig)
            $this->config = new FcsConfig();
        }
    }

    /**
     * Get module instance (lazy loading)
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'forex':
                if ($this->_forex === null) {
                    $this->_forex = new Forex($this);
                }
                return $this->_forex;

            case 'crypto':
                if ($this->_crypto === null) {
                    $this->_crypto = new Crypto($this);
                }
                return $this->_crypto;

            case 'stock':
                if ($this->_stock === null) {
                    $this->_stock = new Stock($this);
                }
                return $this->_stock;

            default:
                throw new \Exception("Property {$name} does not exist");
        }
    }

    /**
     * Set request timeout
     *
     * @param int $seconds Timeout in seconds
     * @return self
     */
    public function setTimeout(int $seconds): self
    {
        $this->config->timeout = $seconds;
        return $this;
    }

    /**
     * Get config
     *
     * @return FcsConfig
     */
    public function getConfig(): FcsConfig
    {
        return $this->config;
    }

    /**
     * Generate token for frontend use
     * Only works when authMethod is 'token'
     *
     * @return array ['_token' => string, '_expiry' => int, '_public_key' => string]
     */
    public function generateToken(): array
    {
        return $this->config->generateToken();
    }

    /**
     * Make API request (POST with form data)
     *
     * @param string $endpoint API endpoint
     * @param array $params Request parameters
     * @return array|null
     */
    public function request(string $endpoint, array $params = []): ?array
    {
        // Add authentication parameters
        $authParams = $this->config->getAuthParams();
        $params = array_merge($params, $authParams);

        $url = self::BASE_URL . $endpoint;

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config->timeout,
            CURLOPT_CONNECTTIMEOUT => $this->config->connectTimeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => '',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'Accept-Encoding: gzip, deflate'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            $this->lastResponse = [
                'status' => false,
                'code' => 0,
                'msg' => 'cURL Error: ' . $error,
                'response' => null
            ];
            return null;
        }

        $data = json_decode($response, true);

        if ($data === null) {
            $this->lastResponse = [
                'status' => false,
                'code' => $httpCode,
                'msg' => 'Invalid JSON response',
                'response' => null
            ];
            return null;
        }

        $this->lastResponse = $data;
        return $data;
    }

    /**
     * Get last response
     *
     * @return array
     */
    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }

    /**
     * Get response data only
     *
     * @return mixed
     */
    public function getResponseData()
    {
        return $this->lastResponse['response'] ?? null;
    }

    /**
     * Check if last request was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return ($this->lastResponse['status'] ?? false) === true;
    }

    /**
     * Get error message from last response
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        if ($this->isSuccess()) {
            return null;
        }
        return $this->lastResponse['msg'] ?? 'Unknown error';
    }
}
