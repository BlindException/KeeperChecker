<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    public function redirectToYahoo()
    {
        $scopes = [
            'fspt-r',
            'fspt-w',
            'fspt-r-sh',
            'fspt-w-sh',
            'fspt-r-adv',
            'fspt-w-adv',
            'fspt-r-draft',
            'fspt-w-draft',
            'fspt-r-lm',
            'fspt-w-lm',
            'fspt-r-lm-adv',
            'fspt-w-lm-adv',
            'fspt-r-lm-draft',
            'fspt-w-lm-draft',
            'fspt-r-lm-transactions',
            'fspt-w-lm-transactions',
            'fspt-r-lm-rosters',
            'fspt-w-lm-rosters',
            'fspt-r-lm-settings',
            'fspt-w-lm-settings',
        ];
        return Socialite::driver('yahoo')
            ->with(['scopes' => $scopes'])
            ->redirect();
    }
}
