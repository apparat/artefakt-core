<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Ports
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

namespace Artefakt\Core\Ports;

use Artefakt\Core\Domain\Contract\CollectionInterface;
use Artefakt\Core\Domain\Exceptions\OutOfBoundsException as DomainOutOfBoundsException;
use Artefakt\Core\Infrastructure\Cli\Application;
use Artefakt\Core\Infrastructure\Exceptions\OutOfBoundsException;
use Artefakt\Core\Infrastructure\Exceptions\RuntimeException;
use Artefakt\Core\Infrastructure\Facade\Artefakt as InfrastructureArtefakt;
use Artefakt\Core\Infrastructure\Factory\SlugFactory;
use Artefakt\Core\Ports\Contract\NodeInterface;

/**
 * Artefakt Facade
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Ports
 */
class Artefakt extends InfrastructureArtefakt
{
    /**
     * Command plugin
     *
     * @var string
     */
    const COMMAND_PLUGIN = 'command';

    /**
     * Return a CLI application interface
     *
     * @return Application CLI application interface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @api
     */
    public static function cli(): Application
    {
        return new Application(self::plugins(self::COMMAND_PLUGIN));
    }

    /**
     * Return a library node
     *
     * @param string $path Node path
     *
     * @return NodeInterface Library node
     * @throws RuntimeException If the collection is invalid
     * @throws OutOfBoundsException If the path can't be resolved
     * @api
     */
    public static function get(string $path = ''): NodeInterface
    {
        $path           = trim($path);
        $node           = static::collection();
        $slugs          = strlen($path) ? SlugFactory::createFromPath($path) : [];
        $collectionPath = '';

        // Run through the slug path
        foreach ($slugs as $slug) {
            $collectionPath .= "/$slug";

            // If the collection is invalid
            if (!($node instanceof CollectionInterface)) {
                throw new RuntimeException(
                    sprintf(RuntimeException::INVALID_COLLECTION_STR, $collectionPath),
                    RuntimeException::INVALID_COLLECTION
                );
            }

            try {
                $node = $node->find($slug);

                // If the path can't be resolved
            } catch (DomainOutOfBoundsException $e) {
                throw new OutOfBoundsException($e->getMessage(), $e->getCode());
            }
        }

        return $node;
    }
}
