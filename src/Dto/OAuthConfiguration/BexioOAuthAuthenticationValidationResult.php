<?php

namespace CodebarAg\Bexio\Dto\OAuthConfiguration;

use Illuminate\Http\RedirectResponse;

class BexioOAuthAuthenticationValidationResult
{
    public function __construct(
        public readonly bool $isValid,
        public readonly ?RedirectResponse $redirect = null
    ) {}

    public static function success(): self
    {
        return new self(isValid: true);
    }

    public static function failed(?RedirectResponse $redirect = null): self
    {
        return new self(isValid: false, redirect: $redirect);
    }
}
