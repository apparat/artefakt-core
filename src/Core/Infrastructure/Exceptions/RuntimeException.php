<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Exceptions
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

namespace Artefakt\Core\Infrastructure\Exceptions;

/**
 * Runtime Exception
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Infrastructure\Exceptions
 */
class RuntimeException extends \Artefakt\Core\Domain\Exceptions\RuntimeException
{
    /**
     * Could not create directory
     *
     * @var string
     */
    const COULD_NOT_CREATE_DIRECTORY_STR = 'Could not create directory "%s"';
    /**
     * Could not create directory
     *
     * @var int
     */
    const COULD_NOT_CREATE_DIRECTORY = 1529837328;
    /**
     * Invalid cache implementation
     *
     * @var string
     */
    const INVALID_CACHE_IMPLEMENTATION_STR = 'Invalid cache implementation "%s"';
    /**
     * Invalid cache implementation
     *
     * @var int
     */
    const INVALID_CACHE_IMPLEMENTATION = 1529955944;
    /**
     * Invalid file
     *
     * @var string
     */
    const INVALID_FILE_STR = 'Invalid file "%s"';
    /**
     * Invalid file
     *
     * @var int
     */
    const INVALID_FILE = 1530825225;
    /**
     * Invalid node descriptor
     *
     * @var string
     */
    const INVALID_NODE_DESCRIPTOR_STR = 'Invalid node descriptor "%s"';
    /**
     * Invalid node descriptor
     *
     * @var int
     */
    const INVALID_NODE_DESCRIPTOR = 1530912244;
    /**
     * Invalid collection
     *
     * @var string
     */
    const INVALID_COLLECTION_STR = 'Invalid collection "%s"';
    /**
     * Invalid collection
     *
     * @var int
     */
    const INVALID_COLLECTION = 1531946432;
}
