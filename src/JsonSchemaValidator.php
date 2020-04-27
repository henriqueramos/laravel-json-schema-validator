<?php declare(strict_types = 1);

namespace RamosHenrique\JsonSchemaValidator;

use stdClass;

use Swaggest\JsonSchema\Schema as BaseSchema;

class JsonSchemaValidator
{
    protected $jsonSchema;
    protected $rawData;

    public function setSchema(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new JsonSchemaValidatorException(JsonSchemaValidatorException::FILE_DOESNT_EXIST);
        }

        $this->jsonSchema = (string) file_get_contents($filePath);
    }

    public function getSchema(): stdClass
    {
        return json_decode($this->jsonSchema);
    }

    public function setData(string $data): void
    {
        $this->rawData = $data;
    }

    public function getData(): stdClass
    {
        return json_decode($this->rawData);
    }

    public function validate(): bool
    {
        $schema = $this->getSchema();
        $schemaDecode = json_last_error();
        $data = $this->getData();

        $dataDecode = json_last_error();

        if ($schemaDecode != JSON_ERROR_NONE ||
            $dataDecode != JSON_ERROR_NONE
        ) {
            throw new JsonSchemaValidatorException(JsonSchemaValidatorException::WASNT_POSSIBLE_DECODE_PARAMETERS);
        }

        $schema = BaseSchema::import($schema);
        $schema->in($data);

        return true;
    }
}
