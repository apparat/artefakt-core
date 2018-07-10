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
use Artefakt\Core\Domain\Exceptions\RuntimeException;

/**
 * Abstract Node
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Model
 * @property string $name Node name
 * @property string $slug Node name slug
 */
abstract class AbstractNode implements AbstractNodeInterface
{
    /**
     * Properties
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Node constructor
     *
     * @param string $slug Node name slug
     * @param string $name Node name
     */
    public function __construct(string $slug, string $name = null)
    {
        $this->setSlug($slug);
        $this->setName($name ?: $slug);
    }

    /**
     * Set the component name slug
     *
     * @param string $slug Component name slug
     */
    public function setSlug(string $slug)
    {
        $this->properties['slug'] = $this->validateSlug($slug);
    }

    /**
     * Validate a node slug
     *
     * @param string $slug Slug
     *
     * @return string Validated slug
     * @throws RuntimeException If the slug is empty
     */
    protected function validateSlug(string $slug): string
    {
        $slug = trim($slug);

        // If the slug is empty
        if (!strlen($slug)) {
            throw new RuntimeException(
                sprintf(RuntimeException::INVALID_NODE_SLUG_STR, $slug),
                RuntimeException::INVALID_NODE_SLUG
            );
        }

        return $slug;
    }

    /**
     * Set the component name
     *
     * @param string $name Component name
     */
    protected function setName(string $name)
    {
        $name = trim($name);
        if (!strlen($name)) {
            throw new RuntimeException(
                sprintf(RuntimeException::INVALID_NODE_NAME_STR, $name),
                RuntimeException::INVALID_NODE_NAME
            );
        }
        $this->properties['name'] = $name;
    }

    /**
     * Magic property getter
     *
     * @param string $name Property name
     *
     * @return mixed Property value
     * @throws RuntimeException If the property is unknown
     */
    public function __get(string $name)
    {
        // If there's an explicit getter
        if (is_callable([$this, 'get'.ucfirst($name)])) {
            return $this->{'get'.ucfirst($name)}();
        }

        // If the property is known
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        throw new RuntimeException(
            sprintf(RuntimeException::UNKNOWN_NODE_PROPERTY_STR, $name),
            RuntimeException::UNKNOWN_NODE_PROPERTY
        );
    }

    /**
     * Magic property setter
     *
     * @param string $name Property name
     * @param mixed $value Property value
     */
    public function __set(string $name, $value)
    {
        // If there's an explicit setter
        if (is_callable([$this, 'set'.ucfirst($name)])) {
            $this->{'set'.ucfirst($name)}($value);

            return;
        }

        $this->properties[$name] = $value;
    }

    /**
     * Batch-assign properties
     *
     * @param array $properties Properties
     *
     * @return bool Success
     */
    public function assign(array $properties): bool
    {
        // Run through all properties
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }

        return true;
    }
}
