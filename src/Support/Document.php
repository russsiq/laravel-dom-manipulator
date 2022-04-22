<?php

namespace Russsiq\DomManipulator\Support;

// Базовые расширения PHP.
use DOMDocument;

// Сторонние зависимости.
use Illuminate\Validation\ValidationException;
use Russsiq\DomManipulator\Contracts\DOMDocumentContract;

/**
 * Экземпляр Документа.
 */
class Document extends DOMDocument implements DOMDocumentContract
{
    /**
     * [TAG_INVALID_PATTERN description]
     * @const string
     */
    const TAG_INVALID_PATTERN = 'Tag (?P<tag>\w+) invalid';

    /**
     * [HTML5_TRUSTED_TAGS description]
     * @const string[]
     */
    const HTML5_TRUSTED_TAGS = [
        'audio',
        'article',
        'canvas',
        'details',
        'figcaption',
        'figure',
        'footer',
        'header',
        'mark',
        'picture',
        'section',
        'source',
        'summary',
        'video',

    ];

    /**
     * Форматирует вывод, добавляя отступы и дополнительные пробелы.
     * @var bool
     */
    public bool $formatOutput = false;

    /**
     * Указание не убирать лишние пробелы и отступы.
     * По умолчанию TRUE в родительском классе.
     * @var bool
     */
    public bool $preserveWhiteSpace = true;

    /**
     * Загружает DTD и проверяет документ на соответствие.
     * По умолчанию FALSE в родительском классе.
     * @var bool
     */
    public bool $validateOnParse = true;

    /**
     * Загрузить HTML с тегами пятой версии из строки.
     * @param  string  $source
     * @param  integer  $options
     * @return void
     */
    public function loadFiveVersionHTML(string $source, int $options = 0): void
    {
        libxml_use_internal_errors(true);

        parent::loadHTML($source, $options);

        $xmlErrors = [];

        foreach (libxml_get_errors() as $error) {
            $message = trim($error->message);

            if (preg_match('/'.self::TAG_INVALID_PATTERN.'/i', $message, $matches)) {
                if (in_array($matches['tag'], self::HTML5_TRUSTED_TAGS)) {
                    continue;
                }
            }

            $xmlErrors[] = [
                'content' => $message,
            ];
        }

        if ($xmlErrors) {
            throw ValidationException::withMessages($xmlErrors);
        }

        libxml_clear_errors();
    }
}
