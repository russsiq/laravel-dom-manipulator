<?php

namespace Russsiq\DomManipulator\Contracts;

/**
 * Контракт публичных методов Репозитория DOM.
 * @var interface
 */
interface DOMRepositoryContract
{
    /**
     * Удалить символы Эмоджи.
     * @param  string  $content
     * @return string
     */
    public static function removeEmoji(string $content): string;

    /**
     * Выполнить замыкание над каждым узлом с заданным именем.
     * @param  string  $name
     * @param  callable  $callback
     * @return self
     */
    public function each(string $name, callable $callback): self;

    /**
     * Удалить все теги, содержащие переданное имя.
     * @param  string  $name
     * @return self
     */
    public function remove(string $name): self;

    /**
     * Скорректировать теги `pre`:
     *  - оставить единый класс для всех тегов;
     *  - преобразовать значения тегов в HTML-сущности.
     * @return self
     */
    public function revisionPreTag(): self;

    /**
     * Получить строковое представление содержимого текущего Документа.
     * @return string
     */
    public function getContent(): string;
}
