<?php

namespace CodebarAg\Bexio\Requests\CalendarYears;

use CodebarAg\Bexio\Dto\CalendarYears\CalendarYearDTO;
use CodebarAg\Bexio\Dto\CalendarYears\CreateCalendarYearDTO;
use Exception;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateCalendarYearRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        readonly protected array|CreateCalendarYearDTO $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/3.0/accounting/calendar_years';
    }

    protected function defaultBody(): array
    {
        $body = $this->data;

        if (! $body instanceof CreateCalendarYearDTO) {
            $body = CreateCalendarYearDTO::fromArray($body);
        }

        return $body->toArray();
    }

    public function createDtoFromResponse(Response $response): mixed
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
