<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelAzSmsSender
 * @package Orkhanahmadov\LaravelAzSmsSender\Facade
 */
class LaravelAzSmsSender extends Facade {
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'laravelazsmssender';
    }
}