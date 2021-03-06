<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
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

namespace Artefakt\Core\Infrastructure\Facade;

use Artefakt\Core\Infrastructure\Contract\ResettableInterface;
use Artefakt\Core\Infrastructure\Factory\FilesystemNodeFactory;
use Artefakt\Core\Infrastructure\Model\FilesystemCollection;

/**
 * Artefakt Pattern Library
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Artefakt implements ResettableInterface
{
    /**
     * Collection
     *
     * @var FilesystemCollection
     */
    protected static $collection = null;

    /**
     * Return all plugins of a particular type
     *
     * @param string $pluginType Plugin type
     *
     * @return string[] Validated plugin classes
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function plugins(string $pluginType): array
    {
        return Cache::instance()->get('plugins.'.$pluginType, []);
    }

    /**
     * Reset
     *
     * @return void
     */
    public static function reset(): void
    {
        Environment::reset();
        Cache::reset();
    }

    /**
     * Return the library collection instance
     *
     * @return FilesystemCollection Library collection
     */
    protected static function collection()
    {
        // One-time library instantiation
        if (self::$collection === null) {
            $rootDescriptor   = Environment::get(Environment::COMPONENTS).DIRECTORY_SEPARATOR.'collection.json';
            self::$collection = FilesystemNodeFactory::createFromDescriptor($rootDescriptor);
        }

        return self::$collection;
    }
}
