<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Facade
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

use Artefakt\Core\Infrastructure\Factory\CacheFactory;
use Artefakt\Core\Infrastructure\Plugin\Discovery;
use Psr\SimpleCache\CacheInterface;

/**
 * Cache Facade (PSR-16 compatible)
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Facade
 * @see        https://www.php-fig.org/psr/psr-16/
 */
class Cache extends AbstractResettable
{
    /**
     * Singleton instance
     *
     * @var CacheInterface
     */
    protected static $instance = null;
    /**
     * Cache instance
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Cache constructor
     *
     * @param CacheInterface $cache Cache implementation
     */
    protected function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Create and initialize an instance
     *
     * @return CacheInterface Cache instance
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function instance(): CacheInterface
    {
        if (self::$instance === null) {
            self::$instance = new static(CacheFactory::create());

            // Auto-update (if necessary)
            if (self::$instance->cache->get('needs-update', true)) {
                (new Discovery())->discover();
            }
        }

        return self::$instance->cache;
    }
}
