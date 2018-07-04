<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Factory
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

namespace Artefakt\Core\Infrastructure\Factory;

use Artefakt\Core\Domain\Contract\NodeNameInterface;
use Artefakt\Core\Domain\Model\NodeName;
use Cocur\Slugify\Slugify;


/**
 * Node Name Factory
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Factory
 */
class NodeNameFactory
{
    /**
     * Slugify instance
     *
     * @var Slugify
     */
    protected static $slugify = null;

    /**
     * Create a node path
     *
     * @param string $path Node name path
     *
     * @return NodeNameInterface[] Node name path
     */
    public static function createFromPath(string $path): array
    {
        return array_map([static::class, 'createFromString'], explode('/', $path));
    }

    /**
     * Create a node name from a string
     *
     * @param string $name Node name string
     *
     * @return NodeNameInterface Node name
     */
    public static function createFromString(string $name): NodeNameInterface
    {
        $name = urldecode($name);
        $slug = self::getSlugService()->slugify($name);

        return new NodeName($name, $slug);
    }

    /**
     * Return a service instance that can create slugs
     *
     * @return Slugify Slug service instance
     */
    protected static function getSlugService(): Slugify
    {
        if (self::$slugify === null) {
            self::$slugify = new Slugify();
        }

        return self::$slugify;
    }
}
