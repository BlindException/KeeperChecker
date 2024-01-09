<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FantasyAPIClient extends Model
{
    use HasFactory;
    protected $table = "fantasy_api_clients";
    protected $fillable = [
        'user_id',
        'clientable_id',
        'clientable_type',
        'access_token',
        'expires_at',
        'refresh_token',
    ];

    protected $attributes = [
        'access_token' => null,
        'expires_at' => null,
        'refresh_token' => null,
    ];

    public function setAccessTokenAttribute($value)
    {
        $this->access_token = Crypt::encrypt($value);
        $this->save();
    }
    public function getAccessTokenAttribute()
    {
        return Crypt::decrypt($this->attributes['access_token']);
    }
    public function setRefreshTokenAttribute($value)
    {
        $this->refresh_token = Crypt::encrypt($value);
        $this->save();
    }
    public function getRefreshTokenAttribute()
    {
        return Crypt::decrypt($this->attributes['refresh_token']);
    }
    public function setExpiresAtAttribute($value)
    {
        $this->expires_at = Carbon::now()->addSeconds($value);
        $this->save();
    }

    /**
     *@params int $userId The id of the user.
     */
    protected function __construct(int $userId)
    {
        $this->user_id = $userId;
    }

    protected function getFirstAccessToken()
    {
    }
    protected function checkAndRefreshToken()
    {

    }

}
