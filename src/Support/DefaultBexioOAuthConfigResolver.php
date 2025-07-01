<?php

namespace CodebarAg\Bexio\Support;

use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

class DefaultBexioOAuthConfigResolver implements BexioOAuthConfigResolver
{
    public function resolve(): ConnectWithOAuth
    {
        return new ConnectWithOAuth();
    }
}
