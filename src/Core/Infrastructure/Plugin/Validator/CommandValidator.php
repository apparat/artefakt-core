<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Plugin\TypeValidator
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

namespace Artefakt\Core\Infrastructure\Plugin\Validator;

use Artefakt\Core\Ports\Contract\Plugin\CommandInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Command Plugin Validator
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Plugin\Validator
 */
class CommandValidator extends AbstractValidator
{
    /**
     * Validate a plugin
     *
     * @param string $plugin Plugin class name
     *
     * @return bool Plugin class is valid
     */
    public function validate(string $plugin): bool
    {
        return parent::validate($plugin)
               && $this->reflection->implementsInterface(CommandInterface::class)
               && $this->reflection->isSubclassOf(Command::class);

    }
}
