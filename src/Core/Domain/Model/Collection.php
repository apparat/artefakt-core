<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Model
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

namespace Artefakt\Core\Domain\Model;

use Artefakt\Core\Domain\Contract\AbstractNodeInterface;
use Artefakt\Core\Domain\Contract\CollectionInterface;
use Artefakt\Core\Domain\Exceptions\OutOfBoundsException;

/**
 * Component Collection
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Model
 */
class Collection extends AbstractNode implements CollectionInterface
{
    /**
     * Nodes
     *
     * @var AbstractNodeInterface[]
     */
    protected $nodes = [];
    /**
     * Node keys
     *
     * @var string[]
     */
    protected $keys = [];
    /**
     * Internal pointer
     *
     * @var int
     */
    protected $pointer = 0;

    /**
     * Return the current node
     *
     * @return AbstractNodeInterface Current node
     */
    public function current(): AbstractNodeInterface
    {
        $key = $this->keys[$this->pointer];

        return $this->nodes[$key];
    }

    /**
     * Move forward to next node
     */
    public function next(): void
    {
        ++$this->pointer;
    }

    /**
     * Return the key of the current node
     *
     * @return int|null Node key
     */
    public function key(): ?int
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The current node is valid
     */
    public function valid(): bool
    {
        return isset($this->keys[$this->pointer]);
    }

    /**
     * Rewind the Iterator to the first node
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->pointer = 0;
    }

    /**
     * Return the number of nodes in this collection
     *
     * @return int The number of nodes
     */
    public function count()
    {
        return count($this->keys);
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
        $this->nodes[spl_object_hash($node)] = $node;
        $this->update();

        return $this;
    }

    /**
     * Update the internal collection order
     */
    protected function update(): void
    {
        uasort($this->nodes, [$this, 'sortByNodeName']);
        $this->keys    = array_map('strval', array_keys($this->nodes));
        $this->pointer = 0;
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
        unset($this->nodes[spl_object_hash($node)]);
        $this->update();

        return $this;
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
        return isset($this->nodes[spl_object_hash($node)]);
    }

    /**
     * Find a contained node by slug
     *
     * @param string $slug Slug
     *
     * @return AbstractNodeInterface Contained node
     * @throws OutOfBoundsException If the node is unknown
     */
    public function find(string $slug): AbstractNodeInterface
    {
        $slug = $this->validateSlug($slug);

        // Run through all contained nodes
        foreach ($this->nodes as $node) {
            if ($node->slug == $slug) {
                return $node;
            }
        }

        // If the node is unknown
        throw new OutOfBoundsException(
            sprintf(OutOfBoundsException::UNKNOWN_NODE_STR, $slug),
            OutOfBoundsException::UNKNOWN_NODE
        );
    }

    /**
     * Sort two nodes by name
     *
     * @param AbstractNodeInterface $node1 First node
     * @param AbstractNodeInterface $node2 Second node
     *
     * @return int Sorting
     */
    protected function sortByNodeName(AbstractNodeInterface $node1, AbstractNodeInterface $node2): int
    {
        return strnatcasecmp($node1->name, $node2->name);
    }
}
