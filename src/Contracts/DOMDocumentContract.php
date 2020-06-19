<?php

namespace Russsiq\DomManipulator\Contracts;

/**
 * Контракт публичных методов Документа.
 * @var interface
 */
interface DOMDocumentContract
{
    /**
     * Загрузить HTML с тегами пятой версии из строки.
     * @param  string  $source
     * @param  integer  $options
     * @return void
     */
    public function loadFiveVersionHTML(string $source, int $options = 0): void;
}
