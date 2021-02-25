<?php

namespace Bakome\ValidationAttributes\Validator;

use Bakome\ValidationAttributes\Attributes\ValidationRule;
use Bakome\ValidationAttributes\Attributes\ValidationRuleRedirect;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\HttpFoundation\Response;

class ReflectionAttributesByRequestValidator implements Validator
{
    private Request $request;
    private bool $isValid = true;
    private RedirectResponse|JsonResponse|Response|string $response;
    private ValidatorFactory $validator;
    private Config $config;
    private Redirector $redirector;
    private ResponseFactory $responseFactory;

    public function __construct(
        Request $request,
        ValidatorFactory $validator,
        Config $config,
        Redirector $redirector,
        ResponseFactory $responseFactory
    ) {
        $this->request = $request;
        $this->validator = $validator;
        $this->config = $config;
        $this->redirector = $redirector;
        $this->responseFactory = $responseFactory;
    }

    public function validate(): void
    {
        [$controller, $action] = $this->resolveRouteControllerAndAction();

        if (!$controller || !$action) {
            $this->isValid = true;
            return;
        }

        $reflectedMethod = $this->fetchReflectedMethod(
            $controller,
            $action
        );

        $redirectsTo = $this->redirectsTo($reflectedMethod);

        $validationRules = $this->resolveValidationRules($reflectedMethod);

        if (!empty($validationRules)) {
            $validator = $this->validator->make($this->request->all(), $validationRules);

            if (
                (
                    $this->request->expectsJson() ||
                    $this->request->is($this->config->get('validation-attributes.api_pattern'))
                )
                && $validator->fails()
            ) {
                $this->isValid = false;
                $this->response = $this->responseFactory->json(
                    [
                        'message' => $validator->messages(),
                        'errors' => $validator->errors(),
                    ],
                    422
                );

                return;
            }

            if ($redirectsTo) {
                /** @var ValidationRuleRedirect $redirectsTo */
                $redirectsTo = $redirectsTo->newInstance();

                if ($validator->fails()) {
                    $redirectingTo = $this->redirector->to($redirectsTo->getRedirectsTo());
                    $redirectingTo->withErrors($validator);

                    if ($redirectsTo->useInputForValidationMessages()) {
                        $redirectingTo->withInput($this->request->input() ?? []);
                    }

                    $this->isValid = false;
                    $this->response = $redirectingTo;
                    return;
                }
            }

            $validator->validate();
        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function response(): RedirectResponse|JsonResponse|Response|string
    {
        return $this->response;
    }

    private function resolveRouteControllerAndAction(): array|null
    {
        $route = Route::getRoutes()->match($this->request);

        $uses = $route->action['controller'] ?? null;

        if ($uses) {
            return explode('@', $uses);
        }

        return null;
    }

    private function fetchReflectedMethod(
        string $controller,
        string $action
    ): \ReflectionMethod {
        $reflector = new \ReflectionClass($controller);

        return $reflector->getMethod($action);
    }

    private function redirectsTo(
        \ReflectionMethod $reflectedMethod
    ): ?\ReflectionAttribute {
        return $reflectedMethod->getAttributes(ValidationRuleRedirect::class)[0] ?? null;
    }

    private function resolveValidationRules(
        \ReflectionMethod $reflectedMethod
    ): array {
        $validationRules = [];

        foreach ($reflectedMethod->getAttributes(ValidationRule::class) as $attribute) {
            $attributeInstance = $attribute->newInstance();

            $validationRules[$attributeInstance->getParameterName()] = $attributeInstance->getRules();
        };

        return $validationRules;
    }
}
