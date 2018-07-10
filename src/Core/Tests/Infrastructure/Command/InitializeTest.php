<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure\Command
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

namespace Artefakt\Core\Tests\Infrastructure\Command;

use Artefakt\Core\Infrastructure\Cli\Command\Initialize;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Webmozart\PathUtil\Path;

/**
 * Initialize Command Test
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure\Command
 */
class InitializeTest extends AbstractCommandTestBase
{
    /**
     * Test the initialize command
     */
    public function testInitialize()
    {
        $application = new Application();
        $application->add(new Initialize());
        $command       = $application->find('app:init');
        $commandTester = new CommandTester($command);
        $status        = $commandTester->execute([
            'command'      => $command->getName(),
            '--components' => 'build/cmp',
            '--documents'  => 'build/doc',
            '--cache'      => 'build/cac',
        ]);
        $this->assertEquals(0, $status);
        $output = $commandTester->getDisplay();
        $this->assertContains('Pattern Library successfully initialized', $output);
        $this->assertDirectoryExists(Path::makeAbsolute('build/cmp', self::$rootDirectory));
        $this->assertDirectoryExists(Path::makeAbsolute('build/doc', self::$rootDirectory));
        $this->assertDirectoryExists(Path::makeAbsolute('build/cac', self::$rootDirectory));
        $this->assertFileExists(Path::makeAbsolute('.env', self::$rootDirectory));
        $this->assertFileExists(Path::makeAbsolute('build/cmp/collection.json', self::$rootDirectory));
    }
}
