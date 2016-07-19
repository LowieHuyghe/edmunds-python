<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Filesystem\Providers;

use Edmunds\Filesystem\FilesystemManager;
use Illuminate\Filesystem\FilesystemServiceProvider as BaseFilesystemServiceProvider;


/**
 * The filesystem service provider
 */
class FilesystemServiceProvider extends BaseFilesystemServiceProvider
{
    /**
     * Register the filesystem manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('filesystem', function ()
        {
            return new FilesystemManager($this->app);
        });
    }
}