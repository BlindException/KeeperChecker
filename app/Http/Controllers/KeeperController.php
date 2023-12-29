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
            $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/users/' . $user->id . '/leagues', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);
            dd($response);
            $status = $response->getStatusCode();
            $error = json_decode($response->getBody(), true)['error'];
            $body = $response->getBody();
            $data = json_decode($body, true);
            // Now you can access the leagues data
            $leagues = $data['fantasy_content']['leagues'];
            // Do something with the leagues data
            return view('pkeepers.index', ['leagues' => $leagues]);
        } else {
            abort('No Leagues');
        }
    }
}
