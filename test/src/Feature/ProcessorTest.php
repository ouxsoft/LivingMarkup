<?php
/**
 * This file is part of the LivingMarkup package.
 *
 * (c) 2017-2020 Ouxsoft  <contact@ouxsoft.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LivingMarkup\Tests;

use LivingMarkup\Factory\ProcessorFactory;
use LivingMarkup\Processor;
use PHPUnit\Framework\TestCase;

final class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private $processor;

    public function setUp(): void
    {
        $this->processor = ProcessorFactory::getInstance();
    }

    public function tearDown(): void
    {
        unset($this->processor);
    }

    public function testParseStringWithLoadConfig()
    {

        $this->processor->loadConfig(TEST_DIR . 'Resource/config/phpunit.json');

        $test_results = $this->processor->parseString('<html lang="en"><bitwise>
    <arg name="number">2</arg>
    <arg name="count">6</arg>
    <arg name="operator">^</arg>
</bitwise></html>');

        $this->assertStringMatchesFormatFile(TEST_DIR . 'Resource/outputs/index.html', $test_results);
    }

    public function testParseFileWithLoadConfig()
    {

        $this->processor->loadConfig(TEST_DIR . 'Resource/config/phpunit.json');

        $test_results = $this->processor->parseFile(TEST_DIR . 'Resource/inputs/index.html');

        $this->assertStringMatchesFormatFile(TEST_DIR . 'Resource/outputs/index.html', $test_results);
    }

    public function testParseWithDefinitions()
    {

        $this->processor->addElement('Bitwise', '//bitwise', 'LivingMarkup\Test\Bitwise');

        $this->processor->addMethod('onRender', 'Execute for render', 'RETURN_CALL');

        $test_results = $this->processor->parseFile(TEST_DIR . 'Resource/inputs/index.html');

        $this->assertStringMatchesFormatFile(TEST_DIR . 'Resource/outputs/index.html', $test_results);
    }
}
