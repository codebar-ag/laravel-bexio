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

class EditAContactGroupRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $id,
        protected readonly array|CreateEditContactGroupDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact_group/'.$this->id;
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateEditContactGroupDTO) {
            $body = CreateEditContactGroupDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): ContactGroupDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ContactGroupDTO::fromArray($response->json());
    }
}
