<?php

namespace Bakome\ValidationAttributes\Tests\Helpers;

use Bakome\ValidationAttributes\Attributes\ValidationRule;
use Bakome\ValidationAttributes\Attributes\ValidationRuleRedirect;

class ValidationTestController
{
    #[ValidationRule('test-param', 'required|string')]
    public function testSingleFieldAction(): string
    {
        return "success";
    }

    #[ValidationRule('test-param-1', 'required|string')]
    #[ValidationRule('test-param-2', 'required|string')]
    #[ValidationRule('test-param-3', ['required', 'string'])]
    public function testMultipleFieldAction(): string
    {
        return "success";
    }

    public function testActionWithoutValidation(): string
    {
        return "success";
    }

    public function testAjaxActionWithoutValidation(): string
    {
        return '{"state": "success"}';
    }

    #[ValidationRule('test-param', 'required|string')]
    #[ValidationRuleRedirect('/redirected-to')]
    public function testValidationWithRedirect(): string
    {
        return "success";
    }

    #[ValidationRule('test-param', 'required|string')]
    #[ValidationRuleRedirect('/redirected-to', true)]
    public function testValidationWithRedirectAndInput(): string
    {
        return "success";
    }
}
