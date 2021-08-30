<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Arr::macro('getAny', function (array $array, array $keys) {
            foreach ($keys as $key) {
                $value = Arr::get($array, $key);
                if (!empty($value)) {
                    return $value;
                }
            }

            return null;
        });

        Arr::macro('hasAnyValue', function (array $array, array $values) {
            foreach ($array as $val) {
                foreach ($values as $value) {
                    if ($val === $value) {
                        return true;
                    }
                }
            }

            return false;
        });

        if (!App::isLocal()) {
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
