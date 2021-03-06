<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Domain
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

namespace Artefakt\Core\Tests\Domain;

use Artefakt\Core\Domain\Contract\CollectionInterface;
use Artefakt\Core\Domain\Model\Collection;
use Artefakt\Core\Domain\Model\Component;
use Artefakt\Core\Tests\AbstractTestBase;

/**
 * Collection2 tests
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Domain
 */
class CollectionTest extends AbstractTestBase
{
    /**
     * Test the collection
     * @expectedException \Artefakt\Core\Domain\Exceptions\OutOfBoundsException
     * @expectedExceptionCode 1531252812
     */
    public function testCollection()
    {
        $collection = new Collection('collection', 'Collection');
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(0, count($collection));

        $components = [
            new Component('Node A', 'node-a'),
            new Component('Node B', 'node-b'),
        ];

        // Add a first component
        $collection->attach($components[1]);
        $this->assertEquals(1, count($collection));
        $this->assertTrue($collection->contains($components[1]));

        // Add a second component via array access
        $collection->attach($components[0]);
        $this->assertEquals(2, count($collection));

        // Iterate through all components
        foreach ($collection as $index => $component) {
            $this->assertTrue(is_int($index));
            $this->assertInstanceOf(Component::class, $component);
            $this->assertEquals(array_shift($components), $component);

            if ($index == 1) {
                $this->assertEquals(1, count($collection->detach($component)));
            }
        }

        $this->assertInstanceOf(CollectionInterface::class, $collection->find('node-a'));
        $collection->find('unknown');
    }
}
