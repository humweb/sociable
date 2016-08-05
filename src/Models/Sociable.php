<?php

namespace Humweb\Sociable\Models;

/**
 * Class Sociable
 *
 * @package Humweb\SociableConnection\Models
 */
trait Sociable
{

    /**
     * SocialConnection relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function social()
    {
        return $this->hasMany(SocialConnection::class, 'user_id');
    }


    /**
     * Link provider to user
     *
     * @param string $provider
     * @param mixed  $user
     */
    public function attachProvider($provider, $user)
    {
        $version = $this->detectOAuthVersion($user);

        // OAuth One Providers
        if ($version === 1) {
            $data = [
                'token'       => $user->token,
                'tokenSecret' => $user->tokenSecret
            ];
        } // OAuth Two Providers
        else {
            $data = [
                'token'        => $user->token,
                'refreshToken' => $user->refreshToken, // not always provided
                'expiresIn'    => $user->expiresIn,
            ];
        }

        $socialData = SocialConnection::create([
            'social_id'     => $user->id,
            'provider'      => $provider,
            'oauth_version' => $version,
            'data'          => $data,
        ]);

        $this->social()->save($socialData);
    }


    /**
     * Unlink provider from user by provider name(s)
     *
     * @param string|array $provider
     *
     * @return int
     */
    public function detachProviderByName($provider)
    {
        $count = 0;
        $providers = $this->social()->ofProvider($provider)->get();
        foreach ($providers as $provider) {
            $provider->delete();
            $count++;
        }

        return $count;
    }


    /**
     * Unlink providers from user by provider id(s)
     *
     * @param string|array $provider
     *
     * @return int
     */
    public function detachProviderById($provider)
    {
        $count = 0;
        $providers = $this->social()->ofProviderId($provider)->get();
        foreach ($providers as $provider) {
            $provider->delete();
            $count++;
        }
        return $count;
    }


    public function listProviders()
    {
        return $this->social()->lists('provider')->all();
    }


    public function detectOAuthVersion($user)
    {
        return property_exists($user, 'tokenSecret') ? 1 : 2;
    }

}