<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $clientId = getenv('YAHOO_CLIENT_ID');
    $clientSecret = getenv('YAHOO_CLIENT_SECRET');
    $client = new Client();
    $response = $client->request('GET', 'https://fantasysports.yahooapis.com/fantasy/v2/game/nfl/scoreboard;week=1;season=2023', [
        'headers' => [
            'Authorization' => 'Bearer ' . $clientId . ':' . $clientSecret,
        ],
    ]);
    dd($response->getBody()->getContents());
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/keepers/', 'App\Http\Controllers\KeeperController@index')->middleware(['auth', 'verified'])->name('keepers.index');
Route::get('/auth/yahoo', 'App\Http\Controllers\Auth\LoginController@redirectToYahoo');
Route::get('/auth/yahoo/callback', 'App\Http\Controllers\Auth\LoginController@handleYahooCallback');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
