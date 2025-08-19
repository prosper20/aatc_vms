<?php

namespace App\Services;

use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;

class OktaService
{
    private $client;
    private $domain;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $issuer;

    public function __construct()
    {
        $this->client = new Client();
        $this->domain = config('okta.domain');
        $this->clientId = config('okta.client_id');
        $this->clientSecret = config('okta.client_secret');
        $this->redirectUri = config('okta.redirect_uri');
        $this->issuer = config('okta.issuer');
    }

    public function getAuthorizationUrl()
    {
        $state = bin2hex(random_bytes(16));
        session(['okta_state' => $state]);

        $params = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
        ];

        return "https://{$this->domain}/oauth2/default/v1/authorize?" . http_build_query($params);
    }

    public function exchangeCodeForTokens($code, $state)
    {
        // Verify state
        if ($state !== session('okta_state')) {
            throw new \Exception('Invalid state parameter');
        }

        $response = $this->client->post("https://{$this->domain}/oauth2/default/v1/token", [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getUserInfo($accessToken)
    {
        $response = $this->client->get("https://{$this->domain}/oauth2/default/v1/userinfo", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
