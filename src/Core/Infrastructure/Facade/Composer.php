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

use Composer\Script\Event;

/**
 * Composer Scripts
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Composer
{
    /**
     * Post-create-project script
     *
     * @param Event $event Event
     */
    public static function postCreateProjectCmd(Event $event)
    {
        $directories = [
            'components' => Environment::$defaultDirectories[Environment::COMPONENTS],
            'docs'       => Environment::$defaultDirectories[Environment::DOCUMENTS],
            'cache'      => Environment::$defaultDirectories[Environment::CACHE],
        ];
        $extra       = $event->getComposer()->getPackage()->getExtra();
        if (isset($extra['apparat/artefakt'])) {
            $directories = array_merge($directories, (array)$extra['apparat/artefakt']);
        }
        Environment::initialize(
            Filesystem::findComposerRootDirectory(__FILE__),
            $directories['components'],
            $directories['docs'],
            $directories['cache']
        );
    }

    /**
     * Extract and return the Artefakt configuration from a Composer configuration (if any)
     *
     * @param string $composerConfig Composer configuration file
     *
     * @return null|\stdClass Artefakt configuration
     */
    public static function getArtefaktConfig(string $composerConfig): ?\stdClass
    {
        if (!is_file($composerConfig)) {
            return null;
        }

        $composerConfigJson   = file_get_contents($composerConfig);
        $composerConfigObject = json_decode($composerConfigJson);

        return isset($composerConfigObject->extra->{'apparat/artefakt'}) ?
            $composerConfigObject->extra->{'apparat/artefakt'} : null;
    }
}
