<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Ports
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 tollwerk GmbH <info@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Artefakt\Core\Tests\Ports;

use Artefakt\Core\Infrastructure\Cli\Application;
use Artefakt\Core\Infrastructure\Facade\Environment;
use Artefakt\Core\Infrastructure\Model\FilesystemCollection;
use Artefakt\Core\Infrastructure\Model\FilesystemComponent;
use Artefakt\Core\Ports\Artefakt;
use Artefakt\Core\Tests\AbstractTestBase;

/**
 * Artefakt Facade Tests
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Ports
 */
class ArtefaktTest extends AbstractTestBase
{
    /**
     * Main directory
     *
     * @var string
     */
    protected static $mainDirectory;

    /**
     * Setup
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$mainDirectory = Environment::get(Environment::COMPONENTS);
        self::copyRecursive(
            dirname(__DIR__).DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'Components',
            self::$mainDirectory
        );
    }

    /**
     * Tear down
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        rmrdir(self::$mainDirectory);
    }

    /**
     * Test the command line Interface
     */
    public function testCli()
    {
        $cli = Artefakt::cli();
        $this->assertInstanceOf(Application::class, $cli);
    }

    /**
     * Test getting the library collection
     */
    public function testGetRootCollection()
    {
        $rootCollection = Artefakt::get();
        $this->assertInstanceOf(FilesystemCollection::class, $rootCollection);
    }

    /**
     * Test getting a nested collection
     */
    public function testGetCollection()
    {
        $collection = Artefakt::get('level-1/level-2');
        $this->assertInstanceOf(FilesystemCollection::class, $collection);
        $this->assertEquals('level-2', $collection->slug);
        $this->assertEquals(2, count($collection));
    }

    /**
     * Test getting a component
     */
    public function testGetComponent()
    {
        $collection = Artefakt::get('level-1/component-3');
        $this->assertInstanceOf(FilesystemComponent::class, $collection);
        $this->assertEquals('component-3', $collection->slug);
    }

    /**
     * Test getting the library collection with an invalid path
     *
     * @expectedException \Artefakt\Core\Infrastructure\Exceptions\OutOfBoundsException
     * @expectedExceptionCode 1531252812
     */
    public function testGetInvalid()
    {
        $rootCollection = Artefakt::get('invalid');
        $this->assertInstanceOf(FilesystemCollection::class, $rootCollection);
    }
}
