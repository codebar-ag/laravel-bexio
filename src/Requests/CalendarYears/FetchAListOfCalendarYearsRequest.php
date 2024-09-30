<?php

namespace CodebarAg\Bexio\Requests\CalendarYears;

use CodebarAg\Bexio\Dto\CalendarYears\CalendarYearDTO;
use Exception;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class FetchAListOfCalendarYearsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        readonly int $limit = 2000,
        readonly int $offset = 0,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/calendar_years';
    }

    public function defaultQuery(): array
    {
        return [
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

        $calendarYears = collect();

        foreach ($res as $calendarYear) {
            $calendarYears->push(CalendarYearDTO::fromArray($calendarYear));
        }

        return $calendarYears;
    }
}
