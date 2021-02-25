<?php

namespace Bakome\ValidationAttributes\Routing\Middleware;

use Bakome\ValidationAttributes\Validator\ReflectionAttributesByRequestValidator;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Config\Repository as Config;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;

class ValidationRulesByAttributes
{
    private Validator $validator;
    private Config $config;
    private Redirector $redirector;
    private ResponseFactory $responseFactory;

    public function __construct(
        Validator $validator,
        Config $config,
        Redirector $redirector,
        ResponseFactory $responseFactory
    ) {
        $this->validator = $validator;
        $this->config = $config;
        $this->redirector = $redirector;
        $this->responseFactory = $responseFactory;
    }

    public function handle(
        Request $request,
        Closure $next
    ) {
        $validator = new ReflectionAttributesByRequestValidator(
            $request,
            $this->validator,
            $this->config,
            $this->redirector,
            $this->responseFactory
        );

        $validator->validate();

        if ($validator->isValid()) {
            return $next($request);
        }

        return $validator->response();
    }
}
