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
            $code = request()->input('code');
            $state = request()->input('state');
            $accessToken = $user->accessTokenResponseBody['access_token'];



            $client = new Client([


                'headers' => [


                    'Authorization' => 'Bearer ' . $accessToken,


                    'Accept' => 'application/json'


                ]


            ]);



            $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games/leagues');

            $leagues = json_decode($response->getBody())->fantasy_content->users->user->games->game->leagues;
            // Process the leagues data here
            return view('pkeepers.index', ['leagues' => $leagues]);
        } else {
            abort('No Leagues');
        }
    }
}
