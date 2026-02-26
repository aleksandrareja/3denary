<?php

namespace Webkul\CustomInpostPaczkomatyShipping\Services;

use Illuminate\Support\Facades\Http;

class InpostApiService
{
    protected $apiKey;
    protected $organizationId;
    protected $baseUrl;

    public function __construct($apiKey, $organizationId, $environment)
    {
        $this->apiKey = $apiKey;
        $this->organizationId = $organizationId;

        $this->baseUrl = $environment === 'sandbox'
            ? 'https://sandbox-api-shipx-pl.easypack24.net'
            : 'https://api-shipx-pl.easypack24.net';
    }

    public function createShipment($payload)
    {
        return Http::withToken($this->apiKey)
            ->post(
                $this->baseUrl . "/v1/organizations/{$this->organizationId}/shipments",
                $payload
            )
            ->json();
    }
}