<?php

namespace Attributes\Validator;

require(__DIR__ . "/../../autoload.php");

use Data\Bean\Validator\ValidationTestBean;
use PHPBean\Exception\PHPBeanException;
use PHPBean\JSON;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    /**
     * @throws PHPBeanException
     */
    public function testTotal()
    {
        $validationTestBean = ValidationTestBean::getInstance();
        $jsonString = ValidationTestBean::getJsonString();
        $parseObj = JSON::parseObj($jsonString, ValidationTestBean::class);
        self::assertEquals($validationTestBean, $parseObj);
    }
}
