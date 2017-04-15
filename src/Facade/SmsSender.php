<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelAzSmsSender
 * @package Orkhanahmadov\LaravelAzSmsSender\Facade
 */
class SmsSender extends Facade {
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'laravelazsmssender';
    }
}