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
            $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/leagues', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);
            $status = $response->getStatusCode();
            $error = json_decode($response->getBody(), true)['error'];
            dd($error . $status);
            $body = $response->getBody();
            $data = json_decode($body, true);
            // Now you can access the user's profile data
            //$profile = $data['fantasy_content']['users'];
            // Do something with the profile data
            dd($data);
            /*            $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users/' . $user->id . '/leagues;game_keys=nfl', [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Accept' => 'application/json',
                            ],
                        ]);
                        $body = $response->getBody();
                        $data = json_decode($body, true);
                        // Now you can access the leagues data
                        $leagues = $data['fantasy_content']['leagues'];
                        // Do something with the leagues data
                        return view('pkeepers.index', ['leagues' => $leagues]);*/
        } else {
            abort('No Leagues');
        }
    }
}
