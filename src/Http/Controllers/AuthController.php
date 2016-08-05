<?php

namespace Humweb\Sociable\Http\Controllers;

use Humweb\Sociable\Auth\Manager;
use Humweb\Sociable\Models\SocialConnection;
use Illuminate\Routing\Controller;
use Illuminate\Session\SessionManager;
use Laravel\Socialite\Facades\Socialite;

/**
 * AuthController
 *
 * @package ${NAMESPACE}
 */
class AuthController extends Controller
{

    public function getRedirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function getAuthLink(SessionManager $session, Manager $auth, $provider)
    {
        $user = Socialite::driver($provider)->user();

        $dbUser        = $auth->user();
        $link          = SocialConnection::ofCredentials($provider, $user->id)->first();
        $linked        = ! is_null($link);
        $authenticated = ! is_null($dbUser);

        // Link: Authenticated not linked
        if ($authenticated && ! $linked) {

            // Link
            $dbUser->attachProvider($provider, $user);

            return redirect('/')->with('success', 'Account connected to "'.$provider.'" successfully.');
        } // Login: Linked but not authenticated
        elseif ( ! $authenticated && $linked) {
            // Log in user
            $dbUser = $auth->login($link->user_id);

            return redirect('/')->with('success', 'Authentication with "'.$provider.'" was successful');
        } else {

            // Store to connect accounts after login
            $session->put('social_link', ['id' => $user->id, 'provider' => $provider]);

            // No user found - redirect to login
            return redirect('login')->with('error', 'No matching records found, please login to link accounts.');
        }
    }
}