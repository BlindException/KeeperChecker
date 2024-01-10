<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class KeeperController extends Controller
{

    public function index()
    {
        if (request()) {
            $user = Socialite::driver('yahoo')->user();
            $accessToken = $user->accessTokenResponseBody['access_token'];
            $client = new Client();
            try {
                //$response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games;game_keys=nfl;seasons=2023;//teams;team_key=7//transactions;type=drop', [
                $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/transactions;team_key=423.l.142134.t.7;type=drops', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/json',
                    ],
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                dd($responseBodyAsString);
            }
            $body = $response->getBody()->getContents();
            dd($body);
            $xmlObject = simplexml_load_string($body);
            // Now you can access the leagues data

            dd($xmlObject);
            // Do something with the leagues data
            return view('keepers.index', ['leagues' => $xmlObject]);
        } else {
            abort('No Leagues');
        }
    }
}
