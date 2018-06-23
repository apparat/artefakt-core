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

namespace Artefakt\Core\Infrastructure\Cli;

use Artefakt\Core\Ports\Plugin\Registry;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\ExceptionInterface;

/**
 * Artefakt CLI Command
 *
 * @package    Artefakt\Artefakt
 * @subpackage Artefakt\Artefakt\Ports
 */
class App extends Application
{
    /**
     * Command errors
     *
     * @var ExceptionInterface[]
     */
    protected $errors = [];

    /**
     * Artefakt CLI command constructor
     *
     * @param string[] $packageDescriptors Package descriptors
     *
     * @api
     */
    public function __construct()
    {
        parent::__construct('Artefakt Pattern Library CLI Tool');

        // Run through and register all known commands
        foreach (Registry::plugins(Registry::COMMAND_PLUGIN) as $command) {
            try {
                $this->add(new $command());
            } catch (ExceptionInterface $e) {
                $this->errors[] = $e;
            }
        }
    }
}