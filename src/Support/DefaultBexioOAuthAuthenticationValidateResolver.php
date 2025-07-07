<?php

namespace CodebarAg\Bexio\Support;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationValidateResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\BexioOAuthAuthenticationValidationResult;

class DefaultBexioOAuthAuthenticationValidateResolver implements BexioOAuthAuthenticationValidateResolver
{
    public function resolve(BexioConnector $connector): BexioOAuthAuthenticationValidationResult
    {
        return BexioOAuthAuthenticationValidationResult::success();
    }
}
