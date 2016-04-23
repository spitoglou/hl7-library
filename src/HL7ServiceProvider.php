<?php

namespace Spitoglou\HL7;

use Illuminate\Support\ServiceProvider;

/**
 * Class SMSServiceProvider
 * @package Spitoglou\SMS
 */
class HL7ServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing configs
        $this->publishes([
            __DIR__ . '/config/hl7.php' => config_path('hl7.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('HL7Handler', MessageHandler::class);
    }

}
