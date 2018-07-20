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
use Artefakt\Core\Domain\Contract\CollectionInterface;
use Artefakt\Core\Domain\Model\Collection;
use Artefakt\Core\Infrastructure\Contract\LazyLoadingInterface;
use Artefakt\Core\Infrastructure\Factory\FilesystemNodeFactory;
use Artefakt\Core\Infrastructure\Model\Traits\LazyLoadingTrait;

/**
 * Lazy Loading File System Collection
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class FilesystemCollection extends Collection implements LazyLoadingInterface
{
    /**
     * Use the Lazy Loading Trait
     */
    use LazyLoadingTrait;
    /**
     * Descriptor file name
     *
     * @var string
     */
    const DESCRIPTOR = 'collection.json';

    /**
     * Return the current node
     *
     * @return AbstractNodeInterface Current node
     */
    public function current(): AbstractNodeInterface
    {
        $this->load();

        return parent::current();
    }

    /**
     * Load the node from the file system
     *
     * @param int $status Load a particular load status
     *
     * @return AbstractNodeInterface Self reference
     */
    protected function load(int $status = LazyLoadingInterface::LOADED_ALL): AbstractNodeInterface
    {
        $loadProperties = $status & LazyLoadingInterface::LOADED_PROPERTIES;
        if ($loadProperties && !($this->loaded & $loadProperties)) {
            $this->loadProperties();
        }

        $loadChildren = $status & LazyLoadingInterface::LOADED_CHILDREN;
        if ($loadChildren && !($this->loaded & $loadChildren)) {
            $this->loadChildren();
        }

        return $this;
    }

    /**
     * Load the collection children
     */
    protected function loadChildren(): void
    {
        $children = glob(
            $this->directory.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'{collection,component}.json',
            GLOB_BRACE | GLOB_NOSORT
        );

        // Run through all child nodes
        foreach ($children as $child) {
            parent::attach(FilesystemNodeFactory::createFromDescriptor($child));
        }

        $this->loaded |= LazyLoadingInterface::LOADED_CHILDREN;
    }

    /**
     * Move forward to next node
     */
    public function next(): void
    {
        $this->load();
        parent::next();
    }

    /**
     * Return the key of the current node
     *
     * @return int|null Node key
     */
    public function key(): ?int
    {
        $this->load();

        return parent::key();
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The current node is valid
     */
    public function valid(): bool
    {
        $this->load();

        return parent::valid();
    }

    /**
     * Rewind the Iterator to the first node
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->load();
        parent::rewind();
    }

    /**
     * Return the number of nodes in this collection
     *
     * @return int The number of nodes
     */
    public function count()
    {
        $this->load();

        return parent::count();
    }

    /**
     * Attach a node to the collection
     *
     * @param AbstractNodeInterface $node Node
     *
     * @return CollectionInterface Self reference
     */
    public function attach(AbstractNodeInterface $node): CollectionInterface
    {
        $this->load();

        return parent::attach($node);
    }

    /**
     * Detach a node from the collection
     *
     * @param AbstractNodeInterface $node Node
     *
     * @return CollectionInterface Self reference
     */
    public function detach(AbstractNodeInterface $node): CollectionInterface
    {
        $this->load();

        return parent::detach($node);
    }

    /**
     * Test whether this collection contains a particular node
     *
     * @param AbstractNodeInterface $node Node
     *
     * @return bool Collection contains node
     */
    public function contains(AbstractNodeInterface $node): bool
    {
        $this->load();

        return parent::contains($node);
    }

    /**
     * Find a contained node by slug
     *
     * @param string $slug Slug
     *
     * @return AbstractNodeInterface Contained node
     */
    public function find(string $slug): AbstractNodeInterface
    {
        $this->load();

        return parent::find($slug);
    }
}
