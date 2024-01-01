<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'yahoo_access_token',
        'yahoo_expires_at',
        'yahoo_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'yahoo_expires_at' => 'datetime',
    ];
    protected $attributes = [
        'yahoo_access_token' => null,
        'yahoo_expires_at' => null,
        'yahoo_refresh_token' => null,
    ];
    public function setYahooAccessTokenAttribute($value)
    {
        $this->yahoo_access_token = Crypt::encrypt($value);
        $this->save();
    }
    public function getYahooAccessTokenAttribute()
    {
        return Crypt::decrypt($this->attributes['yahoo_access_token']);
    }
    public function setYahooRefreshTokenAttribute($value)
    {
        $this->yahoo_refresh_token = Crypt::encrypt($value);
        $this->save();
    }
    public function getYahooRefreshTokenAttribute()
    {
        return Crypt::decrypt($this->attributes['yahoo_refresh_token']);
    }
    public function setYahooExpiresAtAttribute($value)
    {
        $this->yahoo_expires_at = Carbon::now()->addSeconds($value);
        $this->save();
    }
    private function checkToken()
    {
        if ($this->yahoo_expires_at < now()) {
            $this->getYahooRefreshTokenAttribute();
            $client = new Client();
            //Yahoo access token has expired, use $this->yahoo_refresh_token to get a new access token.
            $response = $client->post('https://api.login.yahoo.com/oauth2/get_token', [
                'form_params' => [
                    'client_id' => env('YAHOO_CLIENT_ID'),
                    'client_secret' => env('YAHOO_CLIENT_SECRET'),
                    'redirect_uri' => env('YAHOO_REDIRECT_URI'),
                    'refresh_token' => $this->yahoo_refresh_token,
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
