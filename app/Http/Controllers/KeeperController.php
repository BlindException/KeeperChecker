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
            // Handle the user data returned by Yahoo Fantasy API
            $code = request()->input('code');
            $state = request()->input('state');
            dd($user);
            $client = new Client();
            $response = $client->request('POST', 'https://api.login.yahoo.com/oauth2/get_token', [
                'form_params' => [
                    'client_id' => env('YAHOO_CLIENT_ID'),
                    'client_secret' => env('YAHOO_CLIENT_SECRET'),
                    'redirect_uri' => env('YAHOO_REDIRECT_URI'),
                    'code' => $code,
                    'grant_type' => 'authorization_code'
                ]
            ]);

            $accessToken = json_decode($response->getBody())->access_token;
            $client = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json'
                ]
            ]);
            $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games/leagues');

            $leagues = json_decode($response->getBody())->fantasy_content->users->user->games->game->leagues;
            // Process the leagues data here
            return view('pkeepers.index', ['leagues' => $leagues,
            ]);
        } else {
            abort('No Leagues');
        }
    }
}
