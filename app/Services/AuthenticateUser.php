<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\Droit\User\Repo\UserInterface;
use Illuminate\Http\Request;

class AuthenticateUser {

    private $socialite;
    private $auth;
    private $users;

    public function __construct(Socialite $socialite, Guard $auth, UserInterface $users)
    {
        $this->socialite = $socialite;
        $this->users     = $users;
        $this->auth      = $auth;
    }

    public function execute($request, $listener, $provider)
    {
        if (!$request) return $this->getAuthorizationFirst($provider);

        $user = $this->users->findByUserNameOrCreate($this->getSocialUser($provider));

        $this->auth->login($user, true);

        return $listener->userHasLoggedIn();
    }

    private function getAuthorizationFirst($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    private function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }
}