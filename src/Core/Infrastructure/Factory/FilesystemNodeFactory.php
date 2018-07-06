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

use Artefakt\Core\Domain\Contract\AbstractNodeInterface;
use Artefakt\Core\Infrastructure\Exceptions\RuntimeException;
use Artefakt\Core\Infrastructure\Model\FilesystemCollection;
use Artefakt\Core\Infrastructure\Model\FilesystemComponent;

/**
 * Filesystem Node Factory
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Factory
 */
class FilesystemNodeFactory
{
    /**
     * Descriptor classes
     *
     * @var array
     */
    protected static $descriptorClasses = [
        'component.json'  => FilesystemComponent::class,
        'collection.json' => FilesystemCollection::class,
    ];

    /**
     * Create a filesystem node from a descriptor
     *
     * @param string $path Descriptor path
     *
     * @return AbstractNodeInterface Filesystem node
     * @throws RuntimeException If the node descriptor is invalid
     */
    public static function createFromDescriptor(string $path): AbstractNodeInterface
    {
        $descriptor = pathinfo($path, PATHINFO_BASENAME);
        if (array_key_exists($descriptor, self::$descriptorClasses)) {
            return new self::$descriptorClasses[$descriptor](dirname($path));
        }

        throw new RuntimeException(
            sprintf(RuntimeException::INVALID_NODE_DESCRIPTOR_STR, $path),
            RuntimeException::INVALID_NODE_DESCRIPTOR
        );
    }
}
