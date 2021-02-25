<?php

namespace Bakome\ValidationAttributes\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION )]
class ValidationRuleRedirect
{
    private ?string $redirectsTo = null;
    private bool $withInput = false;

    public function __construct(
        ?string $redirectsTo = null,
        bool $withInput = false
    ) {
        $this->redirectsTo = $redirectsTo;
        $this->withInput = $withInput;
    }

    public function getRedirectsTo():? string
    {
        return $this->redirectsTo;
    }

    public function useInputForValidationMessages(): bool
    {
        return $this->withInput;
    }
}
