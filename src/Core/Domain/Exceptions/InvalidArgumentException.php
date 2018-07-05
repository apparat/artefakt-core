<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Exceptions
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

namespace Artefakt\Core\Domain\Exceptions;

use Artefakt\Core\Domain\Contract\ExceptionInterface;

/**
 * Invalid Argument Exception
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Domain\Exceptions
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * Invalid node name
     *
     * @var string
     */
    const INVALID_NODE_NAME_STR = 'Invalid node name "%s"';
    /**
     * Invalid node name
     *
     * @var string
     */
    const INVALID_NODE_NAME = 1529435561;
    /**
     * Invalid node slug
     *
     * @var string
     */
    const INVALID_NODE_SLUG_STR = 'Invalid node slug "%s"';
    /**
     * Invalid node slug
     *
     * @var string
     */
    const INVALID_NODE_SLUG = 1529523904;
}
