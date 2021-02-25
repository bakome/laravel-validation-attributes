<?php

namespace Bakome\ValidationAttributes\Validator;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface Validator
{
    public function isValid(): bool;

    public function validate(): void;

    public function response(): RedirectResponse|JsonResponse|Response|string;
}
