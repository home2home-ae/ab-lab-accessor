<?php

namespace ABLab\Accessor;

use Illuminate\Support\Facades\Facade;

class ABLabAccessorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ab-lab-accessor';
    }
}