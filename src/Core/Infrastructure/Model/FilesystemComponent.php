<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Model
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

namespace Artefakt\Core\Infrastructure\Model;

use Artefakt\Core\Domain\Contract\AbstractNodeInterface;
use Artefakt\Core\Domain\Model\Component;
use Artefakt\Core\Infrastructure\Contract\LazyLoadingInterface;
use Artefakt\Core\Infrastructure\Model\Traits\LazyLoadingTrait;

/**
 * Lazy Loading File System Component
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Model
 */
class FilesystemComponent extends Component implements LazyLoadingInterface
{
    /**
     * Use the Lazy Loading Trait
     */
    use LazyLoadingTrait;

    /**
     * Load the node from the file system
     *
     * @param int $status Load a particular load status
     *
     * @return AbstractNodeInterface Self reference
     */
    protected function load(int $status = LazyLoadingInterface::LOADED_ALL): AbstractNodeInterface
    {
        if (!($this->loaded & ($status & LazyLoadingInterface::LOADED_PROPERTIES))) {
            $this->loadProperties();
        }

        return $this;
    }
}