<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Domain
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

namespace Artefakt\Core\Tests\Domain;

use Artefakt\Core\Domain\Model\Component;
use Artefakt\Core\Tests\AbstractTestBase;

/**
 * Component tests
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Domain
 */
class ComponentTest extends AbstractTestBase
{
    /**
     * Test the component
     */
    public function testComponent()
    {
        $componentName = 'component '.rand();
        $componentSlug = preg_replace('/[^a-z\d]/', '-', $componentName);
        $component     = new Component($componentName, $componentSlug);
        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals($componentName, $component->getName());
        $this->assertEquals($componentName.'set', $component->setName($componentName.'set')->getName());
        $this->assertEquals($componentSlug, $component->getSlug());
        $this->assertEquals($componentSlug.'set', $component->setSlug($componentSlug.'set')->getSlug());
    }

    /**
     * Test an invalid component name
     *
     * @expectedException \Artefakt\Core\Domain\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1529435561
     */
    public function testInvalidComponentName()
    {
        new Component('', 'slug');
    }

    /**
     * Test an invalid component slug
     *
     * @expectedException \Artefakt\Core\Domain\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1529523904
     */
    public function testInvalidComponentSlug()
    {
        new Component('Name', '');
    }
}
