<?php

namespace Russsiq\DomManipulator\Contracts;

// Сторонние зависимости.
use Russsiq\DomManipulator\Contracts\DOMDocumentContract;
use Russsiq\DomManipulator\Contracts\DOMRepositoryContract;

/**
 * [DOMFactoryContract description]
 * @var interface
 */
interface DOMFactoryContract
{
    /**
     * Обернуть строку как HTML-документ и получить новый экземпляр Манипулятора.
     * @param  string  $string
     * @param  string  $charset
     * @return self
     */
    public static function wrapAsDocument(string $string, string $charset = 'UTF-8'): self;

    /**
     * Создать экземпляр реализации Документа.
     * @param  string  $charset
     * @return DOMDocumentContract
     */
    public function createDocument(string $charset): DOMDocumentContract;

    /**
     * Получить текущий экземпляр репозитория.
     * @return DOMRepositoryContract
     */
    public function store(): DOMRepositoryContract;

    /**
     * Создать новый экземпляр репозитория с указанной реализацией.
     * @param  DOMDocumentContract  $document
     * @return DOMRepositoryContract
     */
    public function repository(DOMDocumentContract $document): DOMRepositoryContract;
}
