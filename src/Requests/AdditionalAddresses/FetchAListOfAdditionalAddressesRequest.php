<?php

namespace CodebarAg\Bexio\Requests\AdditionalAddresses;

use CodebarAg\Bexio\Dto\AdditionalAddresses\AdditionalAddressDTO;
use CodebarAg\Bexio\Enums\AdditionalAddresses\OrderByEnum;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfAdditionalAddressesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
        readonly string|OrderByEnum $orderBy = 'id',
        readonly int $limit = 500,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/2.0/contact/'.$this->id.'/additional_address';
    }

    public function defaultQuery(): array
    {
        return [
            'orderBy' => $this->orderBy instanceof OrderByEnum ? $this->orderBy->value : $this->orderBy,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    public function createDtoFromResponse(Response $response): Collection
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        $res = $response->json();

        $addresses = collect();

        foreach ($res as $address) {
            $addresses->push(AdditionalAddressDTO::fromArray($address));
        }

        return $addresses;
    }
}
