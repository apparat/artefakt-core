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

use Artefakt\Core\Infrastructure\Cli\App;
use Artefakt\Core\Infrastructure\Environment;
use Artefakt\Core\Ports\Plugin\Registry;

/**
 * Artefakt Facade
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Ports
 */
class Artefakt
{
    /**
     * Application is bootstrapped
     *
     * @var bool
     */
    protected static $bootstrapped = false;

    /**
     * Bootstrap the application
     *
     * @api
     */
    public static function bootstrap(): void
    {
        if (!self::$bootstrapped) {

            // Bootstrap the plugin registry
            $rootDirectory      = Environment::get(Environment::ROOT);
            $packageDescriptors = array_merge(
                glob($rootDirectory.'composer.json'),
                glob($rootDirectory.'vendor'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'composer.json')
            );
            Registry::bootstrap($packageDescriptors);
            self::$bootstrapped = true;
        }
    }

    /**
     * Return a CLI application interface
     *
     * @return App CLI application interface
     * @api
     */
    public static function cli(): App
    {
        self::bootstrap();

        return new App();
    }
}