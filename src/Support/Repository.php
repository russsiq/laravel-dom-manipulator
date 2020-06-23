<?php

namespace Russsiq\DomManipulator\Support;

// Базовые расширения PHP.
use Closure;
use DOMElement;
use DOMNode;
use DOMXPath;
use LibXMLError;

// Сторонние зависимости.
use Russsiq\DomManipulator\Contracts\DOMDocumentContract;
use Russsiq\DomManipulator\Contracts\DOMRepositoryContract;
use Russsiq\DomManipulator\Support\Document;

/**
 * Репозиторий Манипулятора DOM.
 */
class Repository implements DOMRepositoryContract
{
    /**
     * Доверенный класс тега `pre`.
     * @const string
     */
    const PRE_TRUSTED_CLASS = 'ql-syntax';

    /**
     * Текущий экземпляр Документа.
     * @var \DOMDocument|DOMDocumentContract
     */
    protected $document;

    /**
     * Текущее значение содержимого.
     * @var string|null
     */
    protected $content;

    /**
     * Удалить символы Эмоджи.
     * @param  string  $content
     * @return string
     *
     * @source https://medium.com/coding-cheatsheet/remove-emoji-characters-in-php-236034946f51
     */
    public static function removeEmoji(string $content): string
    {
        // Match Emoticons.
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $content);

        // Match Miscellaneous Symbols and Pictographs.
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols.
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols.
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats.
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }

    /**
     * Создать новый экземпляр Репозитория.
     * @param  DOMDocumentContract  $document
     */
    public function __construct(DOMDocumentContract $document)
    {
        $this->document = $document;
    }

    /**
     * Выполнить замыкание над каждым узлом с заданным именем.
     * @param  string  $name
     * @param  callable  $callback
     * @return DOMRepositoryContract
     */
    public function each(string $name, callable $callback): DOMRepositoryContract
    {
        $elements = iterator_to_array(
            $this->document->getElementsByTagName($name)
        );

        foreach ($elements as $index => $element) {
            $callback($element, $index);
        }

        return $this;
    }

    /**
     * Удалить все теги, содержащие переданное имя.
     * @param  string  $tagName
     * @return DOMRepositoryContract
     */
    public function remove(string $tagName): DOMRepositoryContract
    {
        $this->each($tagName, function (DOMElement $element) {
            $element->parentNode->removeChild($element);
        });

        return $this;
    }

    /**
     * Скорректировать теги `pre`:
     *  - оставить единый класс для всех тегов;
     *  - преобразовать значения тегов в HTML-сущности.
     * @return DOMRepositoryContract
     */
    public function revisionPreTag(): DOMRepositoryContract
    {
        $this->each('pre', function (DOMElement $element) {
            $element->setAttribute('class', self::PRE_TRUSTED_CLASS);
            $element->setAttribute('spellcheck', 'false');

            $innerHTML = '';
            $owner = $element->ownerDocument;

            foreach ($element->childNodes as $child) {
                $innerHTML .= $owner->saveHTML($child);
            }

            $element->nodeValue = e($innerHTML, false);
        });

        return $this;
    }

    /**
     * Извлечение массива путей изображений.
     * @return array
     */
    public function extractImages(): array
    {
        $images = [];

        $this->each('img', function (DOMElement $element) use (&$images) {
            $images[] = $element->getAttribute('src');
        });

        return $images;
    }

    /**
     * Получить строковое представление содержимого текущего Документа.
     * @return string
     */
    public function getContent(): string
    {
        $this->content = null;

        [$body, ] = $this->document->getElementsByTagName('body');

        $owner = $body->ownerDocument;

        foreach ($body->childNodes as $child) {
            $this->content .= $owner->saveHTML($child);
        }

        return (string) $this->content;
    }

    /**
     * Преобразовать экземпляр Репозитория в строку.
     * @return string
     */
    public function __toString(): string
    {
        return $this->getContent();
    }
}
