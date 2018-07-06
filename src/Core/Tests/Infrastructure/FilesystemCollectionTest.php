<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
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

namespace Artefakt\Core\Tests\Infrastructure;

use Artefakt\Core\Domain\Contract\AbstractNodeInterface;
use Artefakt\Core\Domain\Model\Component;
use Artefakt\Core\Infrastructure\Model\FilesystemCollection;
use Artefakt\Core\Tests\AbstractTestBase;

/**
 * File System Collection Tests
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
 */
class FilesystemCollectionTest extends AbstractTestBase
{
    /**
     * Root directory
     *
     * @var string
     */
    protected static $rootDirectory;

    /**
     * Set up
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$rootDirectory = dirname(__DIR__).DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR.'Components';
    }

    /**
     * Test the file system collection
     */
    public function testFileSystemCollection()
    {
        $collection = new FilesystemCollection(self::$rootDirectory);
        $this->assertInstanceOf(FilesystemCollection::class, $collection);
        $this->assertEquals(1, count($collection));
        foreach ($collection as $index => $node) {
            $this->assertTrue(is_int($index));
            $this->assertInstanceOf(FilesystemCollection::class, $node);
        }

        $component = new Component('test');
        $this->assertInstanceOf(AbstractNodeInterface::class, $collection->attach($component));
        $this->assertTrue($collection->contains($component));
        $this->assertEquals(2, count($collection));
        $this->assertInstanceOf(AbstractNodeInterface::class, $collection->detach($component));
        $this->assertEquals(1, count($collection));
    }
}
