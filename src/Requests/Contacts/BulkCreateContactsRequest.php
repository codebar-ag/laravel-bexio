<?php

namespace CodebarAg\Bexio\Requests\Contacts;

use CodebarAg\Bexio\Dto\ContactDTO;
use CodebarAg\Bexio\Dto\CreateEditContactDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class BulkCreateContactsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateEditContactDTO $data,
    ) {
        $this->body()->setJsonFlags(JSON_FORCE_OBJECT);
    }

    public function resolveEndpoint(): string
    {
        return '/contact';
    }

    protected function defaultBody(): array
    {
        $data = $this->data;

        if (
            $data instanceof CreateEditContactDTO ||
            (! is_array($data[0]) && ! $data[0] instanceof CreateEditContactDTO)
        ) {
            throw new Exception('Please use the CreateContactRequest for single contact creation.');
        }

        $body = collect();

        foreach ($data as $value) {
            $finalValue = $value;

            if (! $value instanceof CreateEditContactDTO) {
                $finalValue = CreateEditContactDTO::fromArray($value);
            }

            $body->push($finalValue->toArray());
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $contacts = collect();

        foreach ($res as $contact) {
            $contacts->push(ContactDTO::fromArray($contact));
        }

        return $contacts;
    }
}
