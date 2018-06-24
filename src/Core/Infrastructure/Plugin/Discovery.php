<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Service
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

namespace Artefakt\Core\Infrastructure\Plugin;

use Artefakt\Core\Infrastructure\Facade\Cache;
use Artefakt\Core\Infrastructure\Facade\Composer;
use Artefakt\Core\Infrastructure\Facade\Environment;
use Artefakt\Core\Infrastructure\Plugin\Validator\CommandValidator;
use Artefakt\Core\Ports\Artefakt;

/**
 * Discovery Service
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Service
 */
class Discovery
{
    /**
     * Plugin validators
     *
     * @var string[]
     */
    protected static $pluginValidators = [
        Artefakt::COMMAND_PLUGIN => CommandValidator::class,
    ];
    /**
     * Root directory
     *
     * @var string
     */
    protected $rootDirectory;
    /**
     * Registered plugins
     *
     * @var array[]
     */
    protected $plugins = [];
    /**
     * Number of extension libraries
     *
     * @var int
     */
    protected $libraries = 0;

    /**
     * Discovery constructor.
     */
    public function __construct()
    {
        $this->rootDirectory = Environment::get(Environment::ROOT);
    }

    /**
     * Discover and cache all available plugins
     *
     * @return int Number of extension libraries
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function discover(): int
    {
        $packageDescriptors = array_merge(
            glob($this->rootDirectory.DIRECTORY_SEPARATOR.'composer.json'),
            glob($this->rootDirectory.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR.'composer.json')
        );

        array_map([$this, 'processPackageDescriptors'], $packageDescriptors);

        // Run through and cache all found plugins
        foreach ($this->plugins as $pluginType => $plugins) {
            Cache::instance()->set('plugins.'.$pluginType, $plugins);
        }

        // Disable the needs-update flag
        Cache::instance()->set('needs-update', false);

        return $this->libraries;
    }

    /**
     * Process an installed package
     *
     * @param string $packageDescriptor Installed package
     */
    protected function processPackageDescriptors(string $packageDescriptor): void
    {
        $artefaktConfig     = Composer::getArtefaktConfig($packageDescriptor);
        $isExtensionPackage = false;
        if (isset($artefaktConfig->plugins) && is_object($artefaktConfig->plugins)) {
            // Run through and process all plugin types
            foreach ($artefaktConfig->plugins as $pluginType => $plugins) {
                $isExtensionPackage = $this->processPluginsOfType($pluginType, $plugins) || $isExtensionPackage;
            }
        }

        if ($isExtensionPackage) {
            ++$this->libraries;
        }
    }

    /**
     * Process a list of plugins of a particular type
     *
     * @param string $pluginType Plugin type
     * @param string[] $plugins  Plugin class names
     *
     * @return bool Plugins have been found
     */
    protected function processPluginsOfType(string $pluginType, $plugins): bool
    {
        if (is_object($plugins)) {
            $plugins = (array)$plugins;
        }

        if (!is_array($plugins)) {
            return false;
        }

        $isExtensionPackage = false;
        foreach (array_filter(array_map('trim', $plugins)) as $plugin) {
            $isExtensionPackage = $this->processPluginOfType($pluginType, $plugin) || $isExtensionPackage;
        }

        return $isExtensionPackage;
    }

    /**
     * Process a plugin of a particular type
     *
     * @param string $pluginType Plugin type
     * @param string $plugin     Plugin class name
     *
     * @return bool Plugins have been found
     */
    protected function processPluginOfType(string $pluginType, string $plugin): bool
    {
        $isExtensionPackage = false;
        if ($this->isValidPluginOfType($pluginType, $plugin)) {
            $this->plugins[$pluginType][] = $plugin;
            $isExtensionPackage           = true;
        }

        return $isExtensionPackage;
    }

    /**
     * Test whether a class is a valid plugin class of a particular type
     *
     * @param string $pluginType Plugin type
     * @param string $plugin     Plugin class name
     *
     * @return bool Is a valid plugin class
     */
    protected function isValidPluginOfType(string $pluginType, string $plugin): bool
    {
        return empty(self::$pluginValidators[$pluginType]) || (new self::$pluginValidators[$pluginType])->validate($plugin);
    }
}
