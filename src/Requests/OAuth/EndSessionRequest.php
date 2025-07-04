<?php

namespace CodebarAg\Bexio\Requests\OAuth;

use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;

class EndSessionRequest extends SoloRequest
{
    protected Method $method = Method::GET;

    public function __construct(
    ) {}

    public function resolveEndpoint(): string
    {
        /** @var OpenIDConfigurationDTO $openIDConfiguration */
        $openIDConfiguration = (new OpenIDConfigurationRequest)->send()->dto();

        return $openIDConfiguration->endSessionEndpoint;
    }
}
