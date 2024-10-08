<?php

namespace CodebarAg\Bexio\Requests\Files;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DownloadFileRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/files/'.$this->id.'/download';
    }
}
