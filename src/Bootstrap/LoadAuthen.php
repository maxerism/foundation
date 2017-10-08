<?php

namespace Orchestra\Foundation\Bootstrap;

use Laravie\Authen\BootAuthenProvider;
use Illuminate\Contracts\Foundation\Application;

class LoadAuthen
{
    use BootAuthenProvider;

    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->BootAuthenProvider();
    }
}
