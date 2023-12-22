<?php

namespace CodebarAg\Bexio\Requests\CompanyProfiles;

use CodebarAg\Bexio\Dto\CompanyProfiles\CompanyProfileDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfCompanyProfilesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct()
    {
    }

    public function resolveEndpoint(): string
    {
        return '/2.0/company_profile';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $companyProfiles = collect();

        foreach ($res as $companyProfile) {
            $companyProfiles->push(CompanyProfileDTO::fromArray($companyProfile));
        }

        return $companyProfiles;
    }
}
