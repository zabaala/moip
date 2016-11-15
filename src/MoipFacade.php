<?php

namespace Zabaala\Moip;

use Illuminate\Support\Facades\Facade;

/**
 * @see Moip
 */
class MoipFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'moip'; }
}