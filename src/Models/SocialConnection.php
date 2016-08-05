<?php

namespace Humweb\Sociable\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialAuth
 *
 * @package Humweb\SociableConnection\Models
 */
class SocialConnection extends Model
{

    protected $table = 'social_connections';

    protected $fillable = ['provider', 'user_id', 'social_id', 'oauth_version', 'data'];

    protected $casts = [
        'data' => 'json'
    ];

    /**
     * The users model name.
     *
     * @var string
     */
    protected static $userModel = 'LGL\Core\Auth\Users\EloquentUser';


    public function user()
    {
        return $this->belongsTo(static::$userModel, 'user_id');
    }


    public function scopeOfProvider($query, $provider)
    {
        return is_array($provider) ? $query->whereIn('provider', $provider) : $query->where('provider', $provider);
    }


    public function scopeOfProviderId($query, $socialId)
    {
        return is_array($socialId) ? $query->whereIn('social_id', $socialId) : $query->where('social_id', $socialId);
    }


    public function scopeOfCredentials($query, $provider, $socialId)
    {
        return $query->where('provider', $provider)->where('social_id', $socialId);
    }


    public function getUser()
    {
        return $this->user;
    }


    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user()->associate($user);

        $this->save();
    }


    /**
     * Get the users model.
     *
     * @return string
     */
    public static function getUsersModel()
    {
        return static::$userModel;
    }


    /**
     * Set the users model.
     *
     * @param  string $usersModel
     *
     * @return void
     */
    public static function setUsersModel($usersModel)
    {
        static::$userModel = $usersModel;
    }

}