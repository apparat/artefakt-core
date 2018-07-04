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

use Artefakt\Core\Domain\Contract\NodeNameInterface;
use Artefakt\Core\Infrastructure\Factory\NodeNameFactory;
use Artefakt\Core\Tests\AbstractTestBase;

/**
 * Node Name Factory Test
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
 */
class NodeNameFactoryTest extends AbstractTestBase
{
    /**
     * Test the node name creation from a string
     */
    public function testCreateNodeNameFromString()
    {
        $nodeNameString = 'Test Component';
        $nodeName       = NodeNameFactory::createFromString($nodeNameString);
        $this->assertInstanceOf(NodeNameInterface::class, $nodeName);
        $this->assertEquals('test-component', $nodeName->getSlug());
        $this->assertEquals($nodeNameString, $nodeName->getName());

        // Test an encoded slash
        $this->assertEquals(
            'test-component',
            NodeNameFactory::createFromString('Test'.rawurlencode('/').'Component')->getSlug()
        );
    }

    /**
     * Test the creation of a node name path
     */
    public function testCreateNodeNamesFromPath()
    {
        $nodeNamePathString = 'Test Component/Sub'.rawurlencode('/').'Component';
        $nodeNamePathSlugs  = ['test-component', 'sub-component'];
        $nodeNamePath       = NodeNameFactory::createFromPath($nodeNamePathString);
        $this->assertTrue(is_array($nodeNamePath));
        foreach ($nodeNamePath as $nodeName) {
            $this->assertInstanceOf(NodeNameInterface::class, $nodeName);
            $this->assertEquals(array_shift($nodeNamePathSlugs), $nodeName->getSlug());
        }
    }
}
