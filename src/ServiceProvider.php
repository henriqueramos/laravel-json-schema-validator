<?php declare(strict_types = 1);

namespace RamosHenrique\JsonSchemaValidator;

use Exception;

use Illuminate\Support\Facades\Validator as BaseValidator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Validation\ValidationException;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
            __DIR__.'/../config/json_schema_validator.php' => config_path('json_schema_validator.php'),
            ],
            'json_schema_validator'
        );

        BaseValidator::extend('json_schema_validator', static function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) != 1) {
                throw ValidationException::withMessages([
                    'json_schema_validator' => JsonSchemaValidatorException::INVALID_PARAMETERS_QUANTITY,
                ]);
            }

            $filePath = config('json_schema_validator.storage_path') . '/' . $parameters[0];

            try {
                $JsonValidate = new JsonSchemaValidator();

                $JsonValidate->setSchema($filePath);
                $JsonValidate->setData($value);

                return $JsonValidate->validate();
            } catch (Exception $e) {
                throw ValidationException::withMessages([
                    'json_schema_validator' => $e->getMessage(),
                ]);
            }
        });
    }

    /**
    * Make config publishment optional by merging the config from the package.
    *
    * @return  void
    */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/json_schema_validator.php',
            'json_schema_validator'
        );
    }
}
