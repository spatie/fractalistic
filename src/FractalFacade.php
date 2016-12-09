<?php

namespace Spatie\Fractalistic;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\Fractalistic\Fratal
 */
class FractalFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fractalistic';
    }
}
