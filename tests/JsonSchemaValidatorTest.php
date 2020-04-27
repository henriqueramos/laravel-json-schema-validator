<?php declare(strict_types = 1);

namespace RamosHenrique\JsonSchemaValidator\Tests;

use PHPUnit\Framework\TestCase;

use Swaggest\JsonSchema\Exception\{
    NumericException,
    ObjectException
};

use RamosHenrique\JsonSchemaValidator\JsonSchemaValidator;

class JsonSchemaValidatorTest extends TestCase
{
    protected function retrieveFilePath(string $filename): string
    {
        return __DIR__ . '/resources/' . $filename;
    }

    protected function retrieveDecodedPayload(string $filename): string
    {
        return file_get_contents($this->retrieveFilePath($filename));
    }

    public function testValidationWithValidInformation(): void
    {
        $schema = new JsonSchemaValidator();
        $schema->setSchema($this->retrieveFilePath('schema.json'));
        $schema->setData($this->retrieveDecodedPayload('payload.json'));

        $this->assertTrue($schema->validate(), 'True expected on validate result');
    }

    public function testValidationWithWrongVersionPayload(): void
    {
        $this->expectException(NumericException::class);
        $this->expectExceptionMessage('Value less than 11 expected, 12 received');

        $schema = new JsonSchemaValidator();
        $schema->setSchema($this->retrieveFilePath('schema.json'));
        $schema->setData($this->retrieveDecodedPayload('invalidPayloads/version.json'));

        $schema->validate();
    }

    public function testValidationWithForgottenAgePayload(): void
    {
        $this->expectException(ObjectException::class);
        $this->expectExceptionMessage('Required property missing: age');

        $schema = new JsonSchemaValidator();
        $schema->setSchema($this->retrieveFilePath('schema.json'));
        $schema->setData($this->retrieveDecodedPayload('invalidPayloads/age.json'));

        $schema->validate();
    }
}
