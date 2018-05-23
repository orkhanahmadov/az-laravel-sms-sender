<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelAzSmsSender.
 */
class SmsSender extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelazsmssender';
    }
}
