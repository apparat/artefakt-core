<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
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

namespace Artefakt\Core\Tests\Infrastructure {

    use Artefakt\Core\Infrastructure\Facade\Environment;
    use Artefakt\Core\Tests\AbstractTestBase;
    use Webmozart\PathUtil\Path;

    /**
     * Environment Tests
     *
     * @package    Artefakt\Core
     * @subpackage Artefakt\Core\Tests\Infrastructure
     */
    class EnvironmentTest extends AbstractTestBase
    {
        /**
         * Test tear down
         */
        public function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
        {
            parent::tearDown();
            putenv('MOCK_MKDIR');
        }

        /**
         * Test the environment
         *
         * @expectedException \Artefakt\Core\Infrastructure\Exceptions\DomainException
         * @expectedExceptionCode 1529739851
         */
        public function testEnvironment()
        {
            $rootDirectory = dirname(dirname(dirname(dirname(__DIR__))));
            $this->assertEquals($rootDirectory, Environment::get(Environment::ROOT));
            $this->assertEquals('default', Environment::get('unknown', 'default'));
            Environment::get('invalid');
        }

        /**
         * Test the initialization of a new environment
         */
        public function testEnvironmentInitialization()
        {
            $cacheDirectory = Environment::get(Environment::CACHE);
            $tempDirectory  = $cacheDirectory.DIRECTORY_SEPARATOR.'tmp';
            $directories    = [md5(rand()), md5(rand()), md5(rand())];
            Environment::initialize($tempDirectory, $directories[0], $directories[1], $directories[2]);
            foreach ($directories as $directory) {
                $this->assertDirectoryExists(Path::makeAbsolute($directory, $tempDirectory));
            }
            $this->assertFileExists($tempDirectory.DIRECTORY_SEPARATOR.'.env');
            $this->assertTrue(unlink($tempDirectory.DIRECTORY_SEPARATOR.'.env'));
            foreach (array_merge($directories, [$tempDirectory]) as $unlink) {
                $this->assertTrue(rmdir(Path::makeAbsolute($unlink, $tempDirectory)));
            }
        }

        /**
         * Test the initialization of a new environment with a directory creation error
         *
         * @expectedException \Artefakt\Core\Infrastructure\Exceptions\RuntimeException
         * @expectedExceptionCode 1529837328
         */
        public function testEnvironmentInitializationError()
        {
            putenv('MOCK_MKDIR=1');
            $cacheDirectory = Environment::get(Environment::CACHE);
            $tempDirectory  = $cacheDirectory.DIRECTORY_SEPARATOR.'tmp';
            $directories    = [md5(rand()), md5(rand()), md5(rand())];
            Environment::initialize($tempDirectory, $directories[0], $directories[1], $directories[2]);
        }
    }
}

namespace Artefakt\Core\Infrastructure\Facade {

    function mkdir($pathname, $mode = 0777, $recursive = false/*, $context = null*/)
    {
        return intval(getenv('MOCK_MKDIR', true)) ? false : \mkdir($pathname, $mode, $recursive);
    }
}
