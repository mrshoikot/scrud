<?php

namespace Mrshoikot\Scrud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mrshoikot\Scrud\Scrud
 */
class Scrud extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mrshoikot\Scrud\Scrud::class;
    }
}
