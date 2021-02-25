<?php

namespace Bakome\ValidationAttributes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Routing\Router;

class ValidationAttributesServiceProvider extends ServiceProvider
{
    public function boot(
        Router $router,
        KernelContract $kernel
    ) {
        if (!config('validation-attributes.enabled')) {
            return;
        }

        $kernel->prependMiddleware(config('validation-attributes.middleware'));
        $kernel->pushMiddleware(config('validation-attributes.middleware'));
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/validation-attributes.php', 'validation-attributes'
        );
    }
}
