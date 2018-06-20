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
use Artefakt\Core\Domain\Exceptions\InvalidArgumentException;

/**
 * Abstract Node
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Model
 */
abstract class AbstractNode implements AbstractNodeInterface
{
    /**
     * Component name
     *
     * @var string
     */
    protected $name;
    /**
     * Component name slug
     *
     * @var string
     */
    protected $slug;

    /**
     * Node constructor
     *
     * @param string $name Node name
     * @param string $slug Node name slug
     */
    public function __construct(string $name, string $slug)
    {
        $this->setName($name);
        $this->setSlug($slug);
    }

    /**
     * Get the component name
     *
     * @return string Component name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the component name
     *
     * @param string $name Component name
     *
     * @return AbstractNodeInterface Self reference
     */
    public function setName(string $name): AbstractNodeInterface
    {
        $name = trim($name);
        if (!strlen($name)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_COMPONENT_NAME_STR, $name),
                InvalidArgumentException::INVALID_COMPONENT_NAME
            );
        }
        $this->name = $name;

        return $this;
    }

    /**
     * Return the component name slug
     *
     * @return string Component name slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set the component name slug
     *
     * @param string $slug Component name slug
     *
     * @return AbstractNode Self reference
     */
    public function setSlug(string $slug): AbstractNode
    {
        $slug = trim($slug);
        if (!strlen($slug)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_COMPONENT_SLUG_STR, $slug),
                InvalidArgumentException::INVALID_COMPONENT_SLUG
            );
        }
        $this->slug = $slug;

        return $this;
    }
}
