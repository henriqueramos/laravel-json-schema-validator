# JSON Schema Validator for Laravel

**JSON Schema Validator for Laravel** it is a `Composer` package created to validate JSON objects against [JSON Schemas](https://json-schema.org) as an `Illuminate\Validation\Validator` custom rule.

[![Build Status](https://travis-ci.org/henriqueramos/laravel-json-schema-validator.svg?branch=master)](https://travis-ci.org/henriqueramos/laravel-json-schema-validator) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/henriqueramos/laravel-json-schema-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/henriqueramos/laravel-json-schema-validator/?branch=master) [![Latest Stable Version](https://poser.pugx.org/henriqueramos/laravel_json_schema_validator/v/stable)](https://packagist.org/packages/henriqueramos/laravel_json_schema_validator) [![License](https://poser.pugx.org/henriqueramos/laravel_json_schema_validator/license)](https://packagist.org/packages/henriqueramos/laravel_json_schema_validator) 

## About
This package works only at Laravel versions `>= 5.8`. And PHP version `>= 7.3`.

We uses the incredible package `swaggest/json-schema`  as a dependency to make everything [works like magic](https://tvtropes.org/pmwiki/pmwiki.php/Main/ClarkesThirdLaw).

## Installation

Add the following line to the `require` section of `composer.json`:

```json
{
    "require": {
        "henriqueramos/laravel_json_schema_validator": "^1.0.0"
    }
}
```

## Setup

1. Run `php artisan vendor:publish --provider="RamosHenrique\JsonSchemaValidator\ServiceProvider"`. This will create on your `config` folder a file named `json_schema_validator.php`.
3. In your `.env` file, add your JSON Schema files storage path with key `JSON_SCHEMA_VALIDATOR_STORAGE_PATH` (i.e `JSON_SCHEMA_VALIDATOR_STORAGE_PATH=storage/jsonschemas/`).
4. Set up your [JSON Schema file](#what-is-a-json-schema)  

## What is a JSON Schema

We supported the following schemas:
* [JSON Schema Draft 7](http://json-schema.org/specification-links.html#draft-7)
* [JSON Schema Draft 6](http://json-schema.org/specification-links.html#draft-6)
* [JSON Schema Draft 4](http://json-schema.org/specification-links.html#draft-4)

Here's an example for JSON Schema and a valid payload for him:
```php
$schemaJson = <<<'JSON'
{
    "type": "object",
    "properties": {
        "uuid": {
            "type": "integer"
        },
        "userId": {
            "type": "integer"
        },
        "items": {
            "type": "array",
            "minimum": 1,
            "items": {
                "$ref": "#/definitions/items"
            }
        }
    },
    "required":[
	    "uuid",
	    "userId",
	    "items",
	],
    "definitions": {
        "items": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "price": {
                    "type": "number"
                },
                "updated": {
                    "type": "string",
                    "format": "date-time"
                }
            },
            "required":[
	            "id",
	            "price"
	        ]
        }
    }
}
JSON;

$payload = <<<'JSON'
{
  "uuid": 8,
  "userId": 1,
  "items": [
	{
    	"id": 12,
        "price": 49.90,
        "updated": "2020-09-07T20:20:39-03:00"
	},
    {
      	"id": 15,
      	"price": 99,
      	"updated": "2020-06-22T16:48:12-03:00"
    }
  ]
}
JSON;
```

## Usage

After save the [JSON Schema file](#what-is-a-json-schema)  on your chosen storage path, you can use this as a [Validation Rule](https://laravel.com/docs/5.8/validation) on your `FormRequest` extended class.

```php
<?php declare(strict_types = 1);

namespace RamosHenrique\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatingPayloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'jsonData' => [
                'bail',
                'required',
                'json',
                'json_schema_validator:validJSONSchema.json',
            ],
        ];
    }

    /**
     * Custom messages for the route validator.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'expectedData.required' => 'required.jsonData',
            'expectedData.json' => 'expectedData.needs.needs.to.be.a.valid.json',
        ];
    }
}
```
