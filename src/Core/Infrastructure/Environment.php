<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
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

namespace Artefakt\Core\Infrastructure;

use Artefakt\Core\Infrastructure\Exceptions\DomainException;
use Composer\Autoload\ClassLoader;
use Dotenv\Dotenv;

/**
 * Environment
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Environment
{
    /**
     * Root directory
     *
     * @var string
     */
    const ROOT = 'ARTEFAKT_ROOT';
    /**
     * Components directory
     *
     * @var string
     */
    const COMPONENTS = 'ARTEFAKT_COMPONENTS';
    /**
     * Documents directory
     *
     * @var string
     */
    const DOCUMENTS = 'ARTEFAKT_DOCUMENTS';
    /**
     * Cache directory
     *
     * @var string
     */
    const CACHE = 'ARTEFAKT_CACHE';
    /**
     * Default directories
     *
     * @var string[]
     */
    public static $defaultDirectories = [
        self::COMPONENTS => 'components',
        self::DOCUMENTS  => 'docs',
        self::CACHE      => 'cache'
    ];
    /**
     * Instance
     *
     * @var Environment
     */
    protected static $instance = null;
    /**
     * Default environment variables
     *
     * @var array
     */
    protected $defaultEnv = [];
    /**
     * Environment variables
     *
     * @var array
     */
    protected $env = [];

    /**
     * Environment constructor
     */
    protected function __construct()
    {
        $this->setDefaults();
        $this->loadEnvironment();
    }

    /**
     * Set the default values
     *
     * @throws \ReflectionException If the class loader class cannot be reflected
     */
    protected function setDefaults(): void
    {
        $composerReflection           = new \ReflectionClass(ClassLoader::class);
        $this->defaultEnv[self::ROOT] = dirname(dirname(dirname($composerReflection->getFileName()))).DIRECTORY_SEPARATOR;
        foreach (self::$defaultDirectories as $key => $directory) {
            $this->defaultEnv[$key] = $this->defaultEnv[self::ROOT].$directory.DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Load environment variables (if available)
     */
    protected function loadEnvironment(): void
    {
        $this->env = $this->defaultEnv;

        // Load additional environment variables
        if (file_exists($this->defaultEnv[self::ROOT].'.env')) {
            $dotenv = new Dotenv($this->defaultEnv[self::ROOT]);
            $dotenv->load();
        }
    }

    /**
     * Return an environment variable value
     *
     * @param string $name   Environment variable name
     * @param mixed $default Default value
     *
     * @return string Environment variable value
     * @throws DomainException If the environment variable is unknown
     */
    public static function get(string $name, $default = null): string
    {
        $self = self::instance();

        // Return the environment variable if it exists
        if (isset($self->env[$name])) {
            return $self->env[$name];
        }

        // Return the default value if one was given
        if (func_num_args() > 1) {
            return $default;
        }

        // If the environment variable is unknown
        throw new DomainException(
            sprintf(DomainException::UNKNOWN_ENVIRONMENT_VARIABLE_STR, $name),
            DomainException::UNKNOWN_ENVIRONMENT_VARIABLE
        );
    }

    /**
     * Singleton instance constructor
     *
     * @return Environment Environment instance
     */
    protected static function instance(): Environment
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Initialize the environment
     *
     * @param string $components Component directory
     * @param string $docs       Document directory
     * @param string $cache      Cache directory
     */
    public static function initialize(string $components, string $docs, string $cache): void
    {
        $self = self::instance();

        print_r(func_get_args());
    }
}
