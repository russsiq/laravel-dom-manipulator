<?php

namespace Russsiq\DomManipulator;

// Сторонние зависимости.
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Russsiq\DomManipulator\Contracts\DOMFactoryContract;
use Russsiq\DomManipulator\Support\Manipulator;

class ManipulatorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Регистрация Класса-обертки для модуля DOM.
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(DOMFactoryContract::class, Manipulator::class);
    }

    /**
     * Получить отложенные службы, предоставляемые поставщиком.
     * @return array
     */
    public function provides(): array
    {
        return [
            DOMFactoryContract::class,

        ];
    }
}
