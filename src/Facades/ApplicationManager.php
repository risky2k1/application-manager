<?php

namespace Risky2k1\ApplicationManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Risky2k1\ApplicationManager\ApplicationManager
 */
class ApplicationManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Risky2k1\ApplicationManager\ApplicationManager::class;
    }
}
