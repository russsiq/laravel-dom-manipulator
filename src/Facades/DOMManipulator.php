<?php

namespace Russsiq\DomManipulator\Facades;

// Сторонние зависимости.
use Illuminate\Support\Facades\Facade;
use Russsiq\DomManipulator\Contracts\DOMFactoryContract;

class DOMManipulator extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DOMFactoryContract::class;
    }
}
