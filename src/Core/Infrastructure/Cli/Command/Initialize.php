<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Command
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

namespace Artefakt\Core\Infrastructure\Cli\Command;

use Artefakt\Core\Infrastructure\Environment;
use Artefakt\Core\Infrastructure\Filesystem;
use Artefakt\Core\Ports\Plugin\Contract\CommandPluginInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Artefakt Setup CLI command
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure
 */
class Initialize extends Command implements CommandPluginInterface
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('app:init')
             ->setDescription('Create and initialize a new pattern library')
             ->setHelp('This command creates the pattern library directories and runs the necessary initialization steps')
             ->addOption(
                 'components',
                 null,
                 InputArgument::OPTIONAL,
                 'The directory where component descriptions are stored',
                 Environment::$defaultDirectories[Environment::COMPONENTS]
             )->addOption(
                'documents',
                null,
                InputArgument::OPTIONAL,
                'The directory where documentation resources are stored',
                Environment::$defaultDirectories[Environment::DOCUMENTS]
            )->addOption(
                'cache',
                null,
                InputArgument::OPTIONAL,
                'The directory where cache resources are stored',
                Environment::$defaultDirectories[Environment::CACHE]
            );
    }

    /**
     * Executes the current command
     *
     * @return null|int Status
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            Environment::initialize(
                Filesystem::findComposerRootDirectory(__FILE__),
                $input->getOption('components'),
                $input->getOption('documents'),
                $input->getOption('cache')
            );
            $output->write('<info>Project initialization successful</info>');
        } catch (\ErrorException $e) {
            $output->write('<error>Error: '.$e->getMessage().'</error>');

            return $e->getCode();
        }

        return 0;
    }
}
