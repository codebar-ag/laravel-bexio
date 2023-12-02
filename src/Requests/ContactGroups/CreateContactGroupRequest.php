<?php

namespace CodebarAg\Bexio\Requests\ContactGroups;

use CodebarAg\Bexio\Dto\ContactGroups\ContactGroupDTO;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateContactGroupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateEditContactGroupDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/contact_group';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditContactGroupDTO) {
            $body = CreateEditContactGroupDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactGroupDTO::fromArray($response->json());
    }
}
