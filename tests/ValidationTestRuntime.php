<?php

namespace Bakome\ValidationAttributes\Tests;

use Bakome\ValidationAttributes\Routing\Middleware\ValidationRulesByAttributes;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Config\Repository as Config;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store as SessionStore;

class ValidationTestRuntime extends TestCase
{
    protected string $response = '';
    protected SessionStore $session;

    protected function setupAndRunAjaxScenario(
        string $controller,
        string $action,
        array $httpRequestParameters = [],
        string $redirectedTo = '/redirected-to',
        array $inputOldParams = []
    ): string {
        $requestMock = $this->createMock(Request::class);

        $requestMock->expects($this->atLeast(0))
            ->method('expectsJson')
            ->willReturn(true);

        return $this->setupAndRunScenario(
            $controller,
            $action,
            $httpRequestParameters,
            $redirectedTo,
            $inputOldParams,
            $requestMock
        );
    }

    protected function setupAndRunScenario(
        string $controller,
        string $action,
        array $httpRequestParameters = [],
        string $redirectedTo = '/redirected-to',
        array $inputOldParams = [],
        Request $requestMockObject = null
    ): string {
        $requestMock = $requestMockObject ?? $this->createMock(Request::class);

        $urlGeneratorMock = $this->createMock(UrlGenerator::class);
        $urlGeneratorMock->expects($this->atLeast(0))
            ->method('getRequest')
            ->willReturn($requestMock);

        $urlGeneratorMock->expects($this->atLeast(0))
            ->method('to')
            ->willReturn($redirectedTo);

        $redirector = new Redirector($urlGeneratorMock);

        $this->session = new SessionStore(
            'laravel-validation-attributes-tests',
            $this->createMock(\SessionHandlerInterface::class)
        );

        $redirector->setSession($this->session);

        $middleware = new ValidationRulesByAttributes(
            new Validator(
                $this->createMock(\Illuminate\Contracts\Translation\Translator::class)
            ),
            $this->createMock(Config::class),
            $redirector,
            new ResponseFactory(
                $this->createMock(Factory::class),
                $redirector
            )
        );

        $routeCollectionMock = $this->createMock(\Illuminate\Routing\RouteCollectionInterface::class);

        $routeMock = $this->createMock(\Illuminate\Routing\Route::class);
        $routeMock->action = [
            'controller' => "$controller@$action"
        ];

        $routeCollectionMock->expects($this->once())
            ->method('match')
            ->with(
                $this->anything()
            )->willReturn($routeMock);

        $requestMock->expects($this->atLeast(0))
            ->method('all')
            ->willReturn($httpRequestParameters);

        $requestMock->expects($this->atLeast(0))
            ->method('input')
            ->willReturn($inputOldParams);

        Route::shouldReceive('getRoutes')
            ->once()
            ->andReturn($routeCollectionMock);

        return $middleware->handle(
            $requestMock,
            function (Request $request) use ($controller, $action) {
                return (new $controller())->$action();
            }
        );
    }
}
