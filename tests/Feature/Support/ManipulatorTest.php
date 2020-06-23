<?php declare(strict_types=1);

namespace Tests\Feature\Support;

// Тестируемый класс.
use Russsiq\DomManipulator\Support\Manipulator;

// Исключения.
// use Russsiq\DomManipulator\Exceptions\ManipulatorException;

// Базовые расширения PHP.

// Сторонние зависимости.
use Russsiq\DomManipulator\Contracts\DOMDocumentContract;
use Russsiq\DomManipulator\Contracts\DOMFactoryContract;
use Russsiq\DomManipulator\Contracts\DOMRepositoryContract;
use Russsiq\DomManipulator\Support\Document;
use Russsiq\DomManipulator\Support\Repository;

// Библиотеки тестирования.
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Russsiq\DomManipulator\Support\Manipulator
 *
 * @cmd phpunit tests\Feature\Support\ManipulatorTest.php
 */
class ManipulatorTest extends TestCase
{
    /**
     * Этот метод вызывается перед запуском
     * первого теста этого класса тестирования.
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Этот метод вызывается перед каждым тестом.
     * @return void
     */
    protected function setUp(): void
    {
    }

    /**
     * Этот метод вызывается после каждого теста.
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * Этот метод вызывается после запуска
     * последнего теста этого класса тестирования.
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Инициализация нового экземпляра Класса-обертки для модуля `DOM`.
     * @param string  $content
     * @param string  $charset
     * @return DOMFactoryContract
     */
    protected function createManipulator(string $content = null, string $charset = 'UTF-8'): DOMFactoryContract
    {
        return new Manipulator($content, $charset);
    }

    /**
     * @test
     * @covers ::__construct
     *
     * Экземпляр Класса-обертки успешно создан.
     * @return void
     */
    public function testSuccessfullyInitiated(): void
    {
        $manipulator = $this->createManipulator();

        $this->assertInstanceOf(DOMFactoryContract::class, $manipulator);
    }

    /**
     * @test
     * @covers ::__construct
     *
     * Тест для файла README.
     * @return void
     */
    public function testExampleForReadmeFile(): void
    {
        $manipulator = $this->createManipulator();

        $content = '<h2>Velit rerum aut adipisci eius et est deserunt et et error</h2>'.PHP_EOL;
        $content .= '<p>Dolore quidem <strong>dolorem</strong> ratione aut similique qui.</p>'.PHP_EOL;
        $content .= '<pre>$manipulator = $this->createManipulator();</pre>'.PHP_EOL;
        $content .= '<script>alert("Hello!");</script>'.PHP_EOL;

        $result = $manipulator::wrapAsDocument($content)
            ->revisionPreTag()
            ->remove('script');

        $this->assertStringContainsString(Repository::PRE_TRUSTED_CLASS, (string) $result);
        $this->assertStringNotContainsString('<script>alert("Hello!");</script>', (string) $result);

        $expected = '<h2>Velit rerum aut adipisci eius et est deserunt et et error</h2>'.PHP_EOL;
        $expected .= '<p>Dolore quidem <strong>dolorem</strong> ratione aut similique qui.</p>'.PHP_EOL;
        $expected .= '<pre class="ql-syntax" spellcheck="false">$manipulator = $this-&gt;createManipulator();</pre>'.PHP_EOL.PHP_EOL;

        $this->assertEquals($expected, (string) $result);
    }
}
