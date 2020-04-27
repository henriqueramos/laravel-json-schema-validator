<?php declare(strict_types = 1);

namespace RamosHenrique\JsonSchemaValidator;

use Exception;

class JsonSchemaValidatorException extends Exception
{
    public const FILE_DOESNT_EXIST = 'json.schema.and.data.should.exists';
    public const INVALID_PARAMETERS_QUANTITY = 'json_schema_validator.expects.one.parameter';
    public const WASNT_POSSIBLE_DECODE_PARAMETERS = 'wrong.decoding.parameters.during.validate';
}
