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

namespace Artefakt\Core\Infrastructure\Facade;

use Artefakt\Core\Infrastructure\Exceptions\DomainException;
use Artefakt\Core\Infrastructure\Exceptions\RuntimeException;
use Codervio\Envmanager\Enveditor;
use Codervio\Envmanager\Envparser;
use Composer\Autoload\ClassLoader;
use Dotenv\Dotenv;
use Webmozart\PathUtil\Path;

/**
 * Environment
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Environment extends AbstractResettable
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
     * Cache implementation
     *
     * @var string
     */
    const CACHE_IMPLEMENTATION = 'ARTEFAKT_CACHE_IMPLEMENTATION';
    /**
     * Default directories
     *
     * @var string[]
     */
    public static $defaultDirectories = [
        self::COMPONENTS => 'components',
        self::DOCUMENTS  => 'documents',
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
        $this->defaultEnv[self::ROOT] = dirname(dirname(dirname($composerReflection->getFileName())));
        $this->mergeValues($this->defaultEnv, self::$defaultDirectories);
    }

    /**
     * Merge environment values
     *
     * @param array $array  Value store
     * @param array $values Environment values
     */
    protected function mergeValues(array &$array, array $values): void
    {
        foreach ($values as $key => $value) {
            switch ($key) {
                case self::COMPONENTS:
                case self::DOCUMENTS:
                case self::CACHE:
                    $array[$key] = Path::makeAbsolute($value, $this->defaultEnv[self::ROOT]);
                    break;
                default:
                    $array[$key] = $value;
            }
        }
    }

    /**
     * Load environment variables (if available)
     */
    protected function loadEnvironment(): void
    {
        $this->env = $this->defaultEnv;

        // Load additional environment variables
        if (file_exists($this->defaultEnv[self::ROOT].DIRECTORY_SEPARATOR.'.env')) {
            $dotenv = new Dotenv($this->defaultEnv[self::ROOT]);
            $dotenv->load();
        }



        $this->mergeValues($this->env, getenv());
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
     * @param string $root       Root directory
     * @param string $components Component directory
     * @param string $documents  Document directory
     * @param string $cache      Cache directory
     */
    public static function initialize(string $root, string $components, string $documents, string $cache): void
    {
        $directories  = [self::COMPONENTS => $components, self::DOCUMENTS => $documents, self::CACHE => $cache];
        $dotEnvParser = new Envparser("$root/.env", true);
        $dotEnvEditor = new Enveditor($dotEnvParser);

        // Run through all necessary directories
        foreach ($directories as $key => $directory) {
            $absDirectory = Path::makeAbsolute($directory, $root);
            if (!is_dir($absDirectory) && !mkdir($absDirectory, 0755, true)) {
                throw new RuntimeException(
                    sprintf(RuntimeException::COULD_NOT_CREATE_DIRECTORY_STR, $directory),
                    RuntimeException::COULD_NOT_CREATE_DIRECTORY
                );
            }

            // Save the directory to the environment
            $dotEnvEditor->persist($key, $directory);
        }

        // Save the environment variables
        $dotEnvEditor->save();
    }
}
