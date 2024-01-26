<?php

namespace Trecobat\LaravelDocugenerateClient\src\Providers;

use Illuminate\Support\ServiceProvider;

class DocugenerateClientProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/docugenerate.php';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();
    }

    private function publish()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('docugenerate.php')
        ], 'config');
    }


    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'docugenerate'
        );
    }
}
