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
            dd($user->accessTokenResponseBody);
        } else {
            abort('No Leagues');
        }
    }
}
