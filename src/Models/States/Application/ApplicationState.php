<?php

namespace Risky2k1\ApplicationManager\Models\States\Application;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ApplicationState extends State
{
    abstract public function text(): string;

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Approved::class);
    }
}
