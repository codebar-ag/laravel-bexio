<?php

namespace CodebarAg\Bexio\Contracts;

use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

interface BexioOAuthConfigResolver
{
    public function resolve(): ConnectWithOAuth;
}
