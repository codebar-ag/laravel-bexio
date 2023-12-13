<?php

namespace CodebarAg\Bexio\Requests\ManualEntries;

use CodebarAg\Bexio\Dto\ManualEntries\AddFileDTO;
use CodebarAg\Bexio\Dto\ManualEntries\ManualEntryDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasMultipartBody;

class AddFileToAccountingEntryLineRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly int $manual_entry_id,
        readonly int $entry_id,
        readonly protected array|AddFileDTO $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/manual_entries/'.$this->manual_entry_id.'/entries/'.$this->entry_id.'/files';
    }

    protected function defaultBody(): array
    {
        $body = collect();

        if ($this->data instanceof AddFileDTO) {
            $body->push(
                new MultipartValue(
                    name: $this->data->name,
                    value: $this->data->absolute_file_path_or_stream,
                    filename: $this->data->filename,
                )
            );

            return $body->toArray();
        }

        foreach ($this->data as $key => &$value) {
            if (! $value instanceof AddFileDTO) {
                $value = AddFileDTO::fromArray($value);
            }

            $body->push(
                new MultipartValue(
                    name: $value->name,
                    value: $value->absolute_file_path_or_stream,
                    filename: $value->filename . '-' . $key,
                )
            );
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return ManualEntryDTO::fromArray($response->json());
    }
}
