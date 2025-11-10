<?php

namespace CodebarAg\Bexio\Requests\CalendarYears;

use CodebarAg\Bexio\Dto\CalendarYears\CalendarYearDTO;
use Exception;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchACalendarYearRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/calendar_years/'.$this->id;
    }

    public function createDtoFromResponse(Response $response): CalendarYearDTO
    {
        if (! $response->successful()) {
            throw new Exception('Request was not successful. Unable to create DTO.');
        }

        return CalendarYearDTO::fromResponse($response);
    }
}
