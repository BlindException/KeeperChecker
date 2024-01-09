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
                $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games;game_key=nfl/leagues', [
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
            $xmlObject = simplexml_load_string($body);
            // Now you can access the leagues data

            dd($xmlObject->attributes);
            // Do something with the leagues data
            return view('keepers.index', ['leagues' => $xmlObject]);
        } else {
            abort('No Leagues');
        }
    }
}
