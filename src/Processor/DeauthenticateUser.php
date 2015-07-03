<?php namespace Orchestra\Foundation\Processor;

use Orchestra\Contracts\Auth\Guard;
use Orchestra\Contracts\Auth\Command\DeauthenticateUser as Command;
use Orchestra\Contracts\Auth\Listener\DeauthenticateUser as Listener;

class DeauthenticateUser extends Authenticate implements Command
{
    /**
     * Create a new processor instance.
     *
     * @param  \Orchestra\Contracts\Auth\Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Logout a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\DeauthenticateUser  $listener
     *
     * @return mixed
     */
    public function logout(Listener $listener)
    {
        $this->auth->logout();

        return $listener->userHasLoggedOut();
    }
}
