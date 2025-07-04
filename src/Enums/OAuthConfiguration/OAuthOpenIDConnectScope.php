<?php

namespace CodebarAg\Bexio\Enums\OAuthConfiguration;

enum OAuthOpenIDConnectScope: string
{
    case COMPANY_PROFILE = 'company_profile';
    case EMAIL = 'email';
    case OFFLINE_ACCESS = 'offline_access';
    case OPENID = 'openid';
    case PROFILE = 'profile';
}
