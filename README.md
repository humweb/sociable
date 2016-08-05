# Sociable

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Socialite authentication and persistence layer implementation.

## Install

Via Composer

``` bash
$ composer require humweb/sociable
```

**Add ServiceProvider**

In the `providers` array add the service providers for this package.
 ```php
 Humweb\Sociable\ServiceProvider::class
 ```

**Publish configuration in Laravel 5**
```bash  
$ php artisan vendor:publish --provider="Humweb\Sociable\ServiceProvider"
```


**Add `Sociable` trait to user model**
```php
class User extends Authenticatable
{
    use Sociable;
}
```

---

## Getting started

See `Humweb\Sociable\Http\Controllers\AuthController.php` for:
* OAuth login
* Link third-party service to a user account

##### Example AuthController (Auto-link third-party account after normal login)
```php
<?php

class AuthController extends Controller
{
    /**
     * Login
     */
    public function getLogin(Request $request)
    {

        // Remove social data from session upon request
        if ($request->exists('forget_social')) {
            $request->session()->forget('social_link');
        }

        return view('login');
    }


    public function postLogin(Request $request)
    {

        // Gather credentials
        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        if ($user = \Auth::attempt($credentials, $remember)) {

            // Check for social data in session
            if ($request->session()->has('social_link')) {

                // Grab data from session
                $social = $request->session()->get('social_link');
                $user->attachProvider($social['provider'], $social);

                // Remove link data
                $request->session()->forget('social_link');

                return redirect()
                    ->intended('/')
                    ->with('success', 'Account connected to "'.$social['provider'].'" successfully.');
            }

            return redirect()->intended('/');
        }

        // Default error message
        return back()
            ->withInput()
            ->withErrors('Invalid login or password.');
    }

}
```


##### Example message for your login page to remove session info

 This can help if the user did not want to auto link the accounts after login.
```
@if (session()->has('social_link'))
    <div class="alert alert-info">
        Login to link your "{{ session('social_link.provider') }}" and "<Your Site>" accounts. <br>
        If this is not what you want <a href="/login?forget_social">click here</a> to refresh.
    </div>
@endif
```

---

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ phpunit
```

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [Ryan Shofner](http://github.com/ryun)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/humweb/sociable.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/humweb/sociable/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/humweb/sociable.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/humweb/sociable.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/humweb/sociable.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/humweb/sociable
[link-travis]: https://travis-ci.org/humweb/sociable
[link-scrutinizer]: https://scrutinizer-ci.com/g/humweb/sociable/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/humweb/sociable
[link-downloads]: https://packagist.org/packages/humweb/sociable
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors