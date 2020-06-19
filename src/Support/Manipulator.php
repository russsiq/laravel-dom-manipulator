<?php

namespace Russsiq\DomManipulator\Support;

// Исключения.
use Illuminate\Validation\ValidationException;

// Базовые расширения PHP.
use Closure;
use DOMElement;
use DOMNode;
use DOMXPath;
use LibXMLError;

// Зарегистрированные фасады приложения.

// Сторонние зависимости.
use Russsiq\DomManipulator\Contracts\DOMDocumentContract;
use Russsiq\DomManipulator\Contracts\DOMFactoryContract;
use Russsiq\DomManipulator\Contracts\DOMRepositoryContract;
use Russsiq\DomManipulator\Support\Document;

/**
 * Класс-обертка для модуля DOM.
 *
 * @TODO Рассмотреть вариант использования `Illuminate\Support\Manager`
 */
class Manipulator implements DOMFactoryContract
{
    /**
     * Текущий экземпляр Репозитория.
     * @var DOMRepositoryContract
     */
    protected $store;

    /**
     * Текущий экземпляр Документа.
     * @var DOMDocumentContract
     */
    protected $document;

    /**
     * Кодировка документа.
     * @var string
     */
    protected $charset;

    /**
     * Обернуть строку как HTML-документ и получить новый экземпляр Манипулятора.
     * @param  string  $string
     * @param  string  $charset
     * @return DOMFactoryContract
     */
    public static function wrapAsDocument(string $string, string $charset = 'UTF-8'): DOMFactoryContract
    {
        $metaCharset = sprintf(
            '<meta charset="%s">',
            $charset
        );

        $content = '<!DOCTYPE html>'
            .'<html>'
                .'<head>'
                    .$metaCharset
                .'</head>'
                .'<body>'
                    .$string
                    .'<script>alert("Hello!");</script>'
                .'</body>'
            .'</html>';

        return new static($content, $charset);
    }

    /**
     * Создать экземпляр Манипулятора DOM.
     * @param string  $content
     * @param string  $charset
     */
    public function __construct(string $content = null, string $charset = 'UTF-8')
    {
        $this->document = $this->createDocument($charset);

        if (!is_null($content)) {
            $this->document->loadFiveVersionHTML($content);
        }
    }

    /**
     * Создать экземпляр реализации Документа.
     * @param  string  $charset
     * @return DOMDocumentContract
     */
    public function createDocument(string $charset): DOMDocumentContract
    {
        return new Document('1.0', $charset);
    }

    /**
     * Получить текущий экземпляр Репозитория.
     * @return DOMRepositoryContract
     */
    public function store(): DOMRepositoryContract
    {
        if (is_null($this->store)) {
            $this->store = $this->repository($this->document);
        }

        return $this->store;
    }

    /**
     * Создать новый экземпляр Репозитория с указанной реализацией.
     * @param  DOMDocumentContract  $document
     * @return DOMRepositoryContract
     */
    public function repository(DOMDocumentContract $document): DOMRepositoryContract
    {
        return tap(new Repository($document), function ($repository) {
            //
        });
    }

    /**
     * Динамический вызов методов текущего Репозитория.
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->store()->$method(...$parameters);
    }
}
