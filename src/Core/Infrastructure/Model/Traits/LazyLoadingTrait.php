<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Model\Traits
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

namespace Artefakt\Core\Infrastructure\Model\Traits;

use Artefakt\Core\Domain\Contract\AbstractNodeInterface;
use Artefakt\Core\Infrastructure\Contract\LazyLoadingInterface;
use Artefakt\Core\Infrastructure\Facade\Filesystem;

/**
 * Lazy Loading Node Trait
 * @package Artefakt\Core\Infrastructure\Model\Traits
 */
trait LazyLoadingTrait
{
    /**
     * File system directory
     *
     * @var string
     */
    protected $directory;
    /**
     * Node has been loaded
     *
     * @var bool
     */
    protected $loaded = false;

    /**
     * Lazy Loading Node constructor
     *
     * @param string $directory File System Directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * Magic property getter
     *
     * @param string $name Property name
     *
     * @return mixed Property value
     */
    public function __get(string $name)
    {
        $this->load(LazyLoadingInterface::LOADED_PROPERTIES);

        return parent::__get($name);
    }

    /**
     * Magic property setter
     *
     * @param string $name Property name
     * @param mixed $value Property value
     */
    public function __set(string $name, $value)
    {
        $this->load(LazyLoadingInterface::LOADED_PROPERTIES);

        parent::__set($name, $value);
    }

    /**
     * Load the node from the file system
     *
     * @param int $status Load a particular load status
     *
     * @return AbstractNodeInterface Self reference
     */
    abstract protected function load(int $status = LazyLoadingInterface::LOADED_ALL): AbstractNodeInterface;

    /**
     * Load the node properties
     */
    protected function loadProperties(): void
    {
        if ($this->assign(Filesystem::loadJSON($this->directory.DIRECTORY_SEPARATOR.'collection.json'))) {
            $this->loaded |= LazyLoadingInterface::LOADED_PROPERTIES;
        }
    }
}
