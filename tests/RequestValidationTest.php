<?php

namespace Bakome\ValidationAttributes\Tests;

use Bakome\ValidationAttributes\Tests\Helpers\ValidationTestController;
use Illuminate\Validation\ValidationException;

class RequestValidationTest extends ValidationTestRuntime
{
    /** @test */
    public function actionWithoutValidationBasicTest()
    {
        $response = $this->setupAndRunScenario(
            ValidationTestController::class,
            'testActionWithoutValidation'
        );

        $this->assertEquals('success', $response);
    }

    /**
     * @test
     * @dataProvider failActionsDataProvider
     * @param string $action
     */
    public function failValidationTest(string $action)
    {
        $this->expectException(ValidationException::class);

        $this->setupAndRunScenario(
            ValidationTestController::class,
            $action
        );
    }

    public function failActionsDataProvider(): array
    {
        return [
            ['testSingleFieldAction'],
            ['testMultipleFieldAction'],
        ];
    }

    /**
     * @test
     * @dataProvider successActionsDataProvider
     * @param string $action
     * @param array $httpRequestParameters
     */
    public function successValidationTest(string $action, array $httpRequestParameters)
    {
        $response = $this->setupAndRunScenario(
            ValidationTestController::class,
            $action,
            $httpRequestParameters
        );

        $this->assertEquals('success', $response);
    }

    public function successActionsDataProvider(): array
    {
        return [
            ['testSingleFieldAction', [
                'test-param' => 'param-value',
            ]],
            ['testMultipleFieldAction', [
                'test-param-1' => 'param-value',
                'test-param-2' => 'param-value',
                'test-param-3' => 'param-value',
            ]],
        ];
    }

    /** @test */
    public function failValidationTestWithRedirect()
    {
        $response = $this->setupAndRunScenario(
            ValidationTestController::class,
            'testValidationWithRedirect'
        );

        $this->assertStringContainsString(
            'Location:      /redirected-to',
            $response
        );
    }

    /** @test */
    public function failValidationTestWithRedirectAndInput()
    {
        $response = $this->setupAndRunScenario(
            ValidationTestController::class,
            'testValidationWithRedirectAndInput',
            [],
            '/redirected-to',
            [
                'test-old-input' => 'aOldInputValue'
            ]
        );

        $this->assertStringContainsString(
            'Location:      /redirected-to',
            $response
        );

        $this->assertTrue($this->session->hasOldInput('test-old-input'));
    }

    /** @test */
    public function failValidationTestMessages()
    {
        try {
            $response = $this->setupAndRunScenario(
                ValidationTestController::class,
                'testSingleFieldAction'
            );
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('test-param', $exception->errors());
        }
    }

    /** @test */
    public function actionWithoutValidationBasicTestJson()
    {
        $response = $this->setupAndRunAjaxScenario(
            ValidationTestController::class,
            'testAjaxActionWithoutValidation'
        );

        $this->assertEquals('{"state": "success"}', $response);
    }

    /**
     * @test
     * @dataProvider failAjaxActionsDataProvider
     * @param string $action
     * @param array $validResponses
     */
    public function failAjaxValidationTest(string $action, array $validResponses)
    {
        $response = $this->setupAndRunAjaxScenario(
            ValidationTestController::class,
            $action
        );

        foreach ($validResponses as $jsonResponse) {
            $this->assertStringContainsString(
                $jsonResponse,
                $response
            );
        }

        $this->assertStringContainsString(
            'Content-Type:  application/json',
            $response
        );

        $this->assertStringContainsString(
            '"message":',
            $response
        );

        $this->assertStringContainsString(
            '"errors":',
            $response
        );
    }

    public function failAjaxActionsDataProvider(): array
    {
        return [
            ['testSingleFieldAction', ['{"test-param":[""]}']],
            ['testMultipleFieldAction', [
                '"test-param-1":[""]',
                '"test-param-2":[""]',
                '"test-param-3":[""]',
            ]],
        ];
    }
}
