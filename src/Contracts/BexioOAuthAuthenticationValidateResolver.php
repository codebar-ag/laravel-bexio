<?php

namespace CodebarAg\Bexio\Contracts;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\BexioOAuthAuthenticationValidationResult;

interface BexioOAuthAuthenticationValidateResolver
{
    public function resolve(BexioConnector $connector): BexioOAuthAuthenticationValidationResult;
}
