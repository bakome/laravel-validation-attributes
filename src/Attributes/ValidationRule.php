<?php

namespace Bakome\ValidationAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class ValidationRule
{
    private string $parameterName;
    private string | array $rules;

    public function __construct(
        string $parameterName,
        string | array $rules = []
    ) {
        $this->parameterName = $parameterName;
        $this->rules = $rules;
    }

    public function getParameterName(): string
    {
        return $this->parameterName;
    }

    public function getRules(): string | array
    {
        return $this->rules;
    }
}
