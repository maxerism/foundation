<?php

namespace Orchestra\Foundation\Testing\Concerns;

use Orchestra\Installation\InstallerServiceProvider;
use Orchestra\Contracts\Installation\Installation as InstallationContract;

trait WithInstallation
{
    /**
     * Make Orchestra Platform installer.
     *
     * @return \Orchestra\Contracts\Installation\Installation
     */
    protected function makeInstaller(): InstallationContract
    {
        if (! $this->app->bound(InstallationContract::class)) {
            $this->app->register(InstallerServiceProvider::class);
        }

        $installer = $this->app->make(InstallationContract::class);

        $installer->bootInstallerFilesForTesting();
        $installer->migrate();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });

        return $installer;
    }

    /**
     * Install Orchestra Platform and get the administrator user.
     *
     * @param  \Orchestra\Contracts\Installation\Installation|null  $installer
     * @param  array  $config
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    protected function runInstallation(?InstallationContract $installer = null, array $config = [])
    {
        if (is_null($installer)) {
            $installer = $this->makeInstaller();
        }

        $this->artisan('migrate');

        $this->adminUser = $this->createAdminUser();

        $installer->create($this->adminUser, [
            'site_name' => $config['name'] ?? 'Orchestra Platform',
            'email' => $config['email'] ?? 'hello@orchestraplatform.com',
        ]);

        $this->app['orchestra.installed'] = true;

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
            $this->app['orchestra.installed'] = false;
        });

        return $this->adminUser;
    }

    /**
     * Create admin user.
     *
     * @return \Orchestra\Foundation\Auth\User
     */
    abstract protected function createAdminUser();
}
