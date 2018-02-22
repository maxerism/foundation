<?php

namespace Orchestra\Foundation\Testing;

trait Installation
{
    /**
     * The administrator user.
     *
     * @var \Orchestra\Foundation\Auth\User|null
     */
    protected $adminUser;

    /**
     * Define hooks to run installation before each test and run rollback on uninstall.
     *
     * @return void
     */
    public function beginInstallation()
    {
        $this->afterApplicationCreated(function () {
            $this->adminUser = $this->app['orchestra.installed'] === false
                                    ? $this->install()
                                    : $this->app['orchestra.user']->newQuery()->first();
        });
    }
}
