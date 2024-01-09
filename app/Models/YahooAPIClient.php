<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YahooAPIClient extends FantasyAPIClient
{
    use HasFactory;
    protected function getFirstAccessToken()
    {
        $clientId = env('yahoo_client_id');
        $clientSecret = env('yahoo_client_secret');
        $redirectUrl = env('yahoo_redirect_uri');
        $state = 'generate_a_unique_state_value';
        $authorizationUrl = 'https://api.login.yahoo.com/oauth2/request_auth';
        $authorizationUrl .= '?client_id=' . urlencode($clientId);
        $authorizationUrl .= '&redirect_uri=' . urlencode($redirectUrl);
        $authorizationUrl .= '&response_type=code';
        $authorizationUrl .= '&state=' . urlencode($state);
        // Redirect the user to the authorization URL
        header('Location: ' . $authorizationUrl);
        exit;
    }

    protected function checkAndRefreshToken()
    {
        if ($this->expires_at < now()) {
            $this->getYahooRefreshTokenAttribute();
            $client = new Client();
            //Yahoo access token has expired, use $this->refresh_token to get a new access token.
            $response = $client->post('https://api.login.yahoo.com/oauth2/get_token', [
                'form_params' => [
                    'client_id' => env('YAHOO_CLIENT_ID'),
                    'client_secret' => env('YAHOO_CLIENT_SECRET'),
                    'redirect_uri' => env('YAHOO_REDIRECT_URI'),
                    'refresh_token' => $this->refresh_token,
                    'grant_type' => 'refresh_token',
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);
            dd($response);
        }
    }
}
