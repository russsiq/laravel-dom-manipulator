# Класс-обертка для модуля DOM в Laravel 8.x.

Содержание:
1. [Подключение](#Подключение)
1. [Использование](#Использование)
    - [Методы](#Методы)
    - [Фасад `DOMManipulator`](#facade-dom-manipulator)
1. [Тестирование](#Тестирование)
1. [Удаление пакета](#Удаление-пакета)
1. [Лицензия](#Лицензия)

### Подключение

 - **1** Для добавления зависимости в проект на Laravel в файле `composer.json`

    ```json
    "require": {
        "russsiq/laravel-dom-manipulator": "^0.1"
    }
    ```

 - **2** Для подключения в уже созданный проект воспользуйтесь командной строкой:

    ```console
    composer require "russsiq/laravel-dom-manipulator:^0.1"
    ```

 - **3** Если в вашем приложении включен отказ от обнаружения пакетов в директиве `dont-discover` в разделе `extra` файла `composer.json`, то необходимо самостоятельно добавить в файле `config/app.php`:

    - **3.1** Провайдер услуг в раздел `providers`:

        ```php
        Russsiq\DomManipulator\ManipulatorServiceProvider::class,
        ```

    - **3.2** Псевдоним класса (Facade) в раздел `aliases`:

        ```php
        'DOMManipulator' => Russsiq\DomManipulator\Facades\DOMManipulator::class,
        ```

### Использование

<a name="facade-dom-manipulator"></a>
#### Фасад `DOMManipulator`

Для инициализации класса-обертки `Manipulator` вы можете воспользоваться методом `wrapAsDocument` фасада `DOMManipulator`:

```php
use Russsiq\DomManipulator\Facades\DOMManipulator;

// Предположим некое содержимое.
$content = '<h2>Velit rerum aut adipisci eius et est deserunt et et error</h2>'.PHP_EOL;
$content .= '<p>Dolore quidem <strong>dolorem</strong> ratione aut similique qui.</p>'.PHP_EOL;
$content .= '<pre>$manipulator = $this->createManipulator();</pre>'.PHP_EOL;
$content .= '<script>alert("Hello!");</script>'.PHP_EOL;

// Обернем содержимое как HTML-документ.
$result = DOMManipulator::wrapAsDocument($content)
    // Скорректируем теги `pre`.
    ->revisionPreTag()
    // Удалим нежелательные теги с их значениями.
    ->remove('script');

// Распечатаем результат.
print_r((string) $result);

// <h2>Velit rerum aut adipisci eius et est deserunt et et error</h2>
// <p>Dolore quidem <strong>dolorem</strong> ratione aut similique qui.</p>
// <pre class="ql-syntax" spellcheck="false">$manipulator = $this-&gt;createManipulator();</pre>
```

#### Методы

Список доступных публичных методов класса-обертки `Manipulator`:

 - [each](#method-each)
 - [extractImages](#method-extractImages)
 - [remove](#method-remove)
 - [revisionPreTag](#method-revisionPreTag)
 - [getContent](#method-getContent)

<a name="method-extractImages"></a>
##### `extractImages(): array`
Извлечение массива путей изображений.

<a name="method-each"></a>
##### `each(string $name, callable $callback): self`
Выполнить замыкание над каждым узлом с заданным именем.

<a name="method-remove"></a>
##### `remove(string $name): self`
Удалить все теги, содержащие переданное имя.

<a name="method-revisionPreTag"></a>
##### `revisionPreTag(): self`
Скорректировать теги `pre`:
- оставить единый класс для всех тегов;
- преобразовать значения тегов в HTML-сущности.

<a name="method-getContent"></a>
##### `getContent(): string`
Получить строковое представление содержимого текущего Документа.

### Тестирование

Для запуска тестов используйте команду:

```console
composer run-script test
```

Для запуска тестов под Windows 7 используйте команду:

```console
composer run-script test-win7
```

Для формирования agile-документации, генерируемой в HTML-формате и записываемой в файл [tests/testdox.html](tests/testdox.html), используйте команду:

```console
composer run-script testdox
```

### Удаление пакета

Для удаления пакета из вашего проекта на Laravel используйте команду:

```console
composer remove russsiq/laravel-dom-manipulator
```

### Лицензия

`laravel-dom-manipulator` – программное обеспечение с открытым исходным кодом, распространяющееся по лицензии [MIT](LICENSE).
