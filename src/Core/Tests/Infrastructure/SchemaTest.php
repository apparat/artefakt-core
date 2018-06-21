<?php

/**
 * artefakt-core
 *
 * @category   Artefakt
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
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

namespace Artefakt\Core\Tests\Infrastructure;

use Artefakt\Core\Tests\AbstractTestBase;
use JsonSchema\Validator;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * JSON Schema Tests
 *
 * @package    Artefakt\Core
 * @subpackage Artefakt\Core\Tests\Infrastructure
 */
class SchemaTest extends AbstractTestBase
{
    /**
     * Fixture directory
     *
     * @var string
     */
    protected static $fixtureDirectory;
    /**
     * Schema directory
     *
     * @var string
     */
    protected static $schemaDirectory;
    /**
     * JSON Meta Schema
     *
     * @var \stdClass
     */
    protected static $jsonSchema;

    /**
     * Setup
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$schemaDirectory  = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'Infrastructure'
                                  .DIRECTORY_SEPARATOR.'Schema'.DIRECTORY_SEPARATOR;
        self::$fixtureDirectory = dirname(__DIR__).DIRECTORY_SEPARATOR.'Fixture'.DIRECTORY_SEPARATOR;
        $metaSchema             = self::$fixtureDirectory.'Schema'.DIRECTORY_SEPARATOR.'json-schema-draft-07.json';
        self::$jsonSchema       = json_decode(file_get_contents($metaSchema));
    }

    /**
     * Schema file name provider
     *
     * @return array[] Schema file names
     */
    public function provideNames()
    {
        return [
            ['Component.json']
        ];
    }

    /**
     * Test the validity of JSON schemata
     *
     * @param string $schema Schema file name
     *
     * @dataProvider provideNames
     */
    public function testSchema(string $schema)
    {
        $this->validate(self::$schemaDirectory.$schema, self::$jsonSchema);
    }

    /**
     * Validate a data JSON file against a schema
     *
     * @param string $dataFile      Data JSON file
     * @param \stdClass $schemaJson Schema JSON
     */
    protected function validate(string $dataFile, \stdClass $schemaJson)
    {
        $validator = new Validator();
        try {
            $dataJson = json_decode(file_get_contents($dataFile));
            $validator->validate($dataJson, $schemaJson);
            $this->assertTrue($validator->isValid());
        } catch (ExpectationFailedException $e) {
            $message = [sprintf('JSON Schema "%s" does not validate. Violations:', basename($dataFile))];
            foreach ($validator->getErrors() as $error) {
                $message[] = sprintf('- [%s] %s', $error['property'], $error['message']);
            }
            $this->fail(implode(PHP_EOL, $message).PHP_EOL);
        }
    }

    /**
     * Test the validity of JSON data
     *
     * @param string $data Data file name
     *
     * @dataProvider provideNames
     */
    public function testData(string $data)
    {
        $schema = json_decode(file_get_contents(self::$schemaDirectory.$data));
        $this->validate(self::$fixtureDirectory.'Json'.DIRECTORY_SEPARATOR.$data, $schema);
    }
}
