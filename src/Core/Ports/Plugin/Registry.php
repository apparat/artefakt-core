<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Ports\Plugin
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

namespace Artefakt\Core\Ports\Plugin;

use Artefakt\Core\Infrastructure\Contract\PluginValidatorInterface;
use Artefakt\Core\Infrastructure\Plugin\Validator\CommandValidator;

/**
 * Plugin Registry
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Ports\Plugin
 */
class Registry
{
    /**
     * Command plugin
     *
     * @var string
     */
    const COMMAND_PLUGIN = 'command';
    /**
     * Singleton instance
     *
     * @var Registry
     */
    protected static $instance = null;
    /**
     * Plugin validators
     *
     * @var array
     */
    protected static $pluginValidators = [
        self::COMMAND_PLUGIN => CommandValidator::class,
    ];
    /**
     * Registered plugins
     *
     * @var array[]
     */
    protected static $plugins = [];

    /**
     * Registry constructor
     *
     * @param string[] $packageDescriptors Package descriptors
     */
    public function __construct(array $packageDescriptors)
    {
        array_map([$this, 'processPluginPackageDescriptors'], $packageDescriptors);
    }

    /**
     * Bootstrap a registry instance
     *
     * @param string[] $packageDescriptors Package descriptors
     *
     * @return Registry Registry instance
     */
    public static function bootstrap(array $packageDescriptors = []): Registry
    {
        if (self::$instance === null) {
            self::$instance = new static($packageDescriptors);
        }

        return self::$instance;
    }

    /**
     * Return all plugins of a particular type
     *
     * @param string $pluginType Plugin type
     *
     * @return string[] Validated plugin classes
     */
    public static function plugins(string $pluginType): array
    {
        self::bootstrap();

        return empty(self::$plugins[$pluginType]) ? [] : self::$plugins[$pluginType];
    }

    /**
     * Process an installed package
     *
     * @param string $packageDescriptor Installed package
     */
    protected function processPluginPackageDescriptors(string $packageDescriptor): void
    {
        // If the descriptor is not a valid file
        if (!is_file($packageDescriptor)) {
            return;
        }

        $packageDescriptorJson   = file_get_contents($packageDescriptor);
        $packageDescriptorObject = json_decode($packageDescriptorJson);
        if (isset($packageDescriptorObject->extra->artefakt)) {
            array_map(
                [$this, 'processPlugins'],
                (array)$packageDescriptorObject->extra->artefakt
            );
        }
    }

    /**
     * Process a list of plugin descriptors
     *
     * @param \stdClass $pluginDescriptors Plugin descriptors
     */
    protected function processPlugins($pluginDescriptors): void
    {
        // If the plugin descriptors is not an object: Skip
        if (!is_object($pluginDescriptors)) {
            return;
        }

        // Run through and process all plugin types
        foreach ($pluginDescriptors as $pluginType => $plugins) {
            $this->processPluginsOfType($pluginType, $plugins);
        }
    }

    /**
     * Process a list of plugins of a particular type
     *
     * @param string $pluginType Plugin type
     * @param string[] $plugins  Plugin class names
     */
    protected function processPluginsOfType(string $pluginType, $plugins): void
    {
        if (is_object($plugins)) {
            $plugins = (array)$plugins;
        }

        if (!is_array($plugins)) {
            return;
        }

        foreach (array_filter(array_map('trim', $plugins)) as $plugin) {
            $this->processPluginOfType($pluginType, $plugin);
        }
    }

    /**
     * Process a plugin of a particular type
     *
     * @param string $pluginType Plugin type
     * @param string $plugin     Plugin class name
     */
    protected function processPluginOfType(string $pluginType, string $plugin): void
    {
        if ($this->isValidPluginOfType($pluginType, $plugin)) {
            self::$plugins[$pluginType][] = $plugin;
        }
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
        // If there's a validator for this plugin type
        if (isset(self::$pluginValidators[$pluginType])) {
            /** @var PluginValidatorInterface $pluginValidator */
            $pluginValidator = new self::$pluginValidators[$pluginType];

            return $pluginValidator->validate($plugin);
        }

        return true;
    }
}