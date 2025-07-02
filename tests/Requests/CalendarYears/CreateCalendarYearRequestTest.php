<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\CalendarYears\CreateCalendarYearDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\CalendarYears\CreateCalendarYearRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateCalendarYearRequest::class => MockResponse::fixture('CalendarYears/create-a-calendar-year'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateCalendarYearRequest(
        new CreateCalendarYearDTO(
            year: '2017',
            is_vat_subject: true,
            vat_accounting_method: 'effective',
            vat_accounting_type: 'agreed',
            default_tax_income_id: 3,
            default_tax_expense_id: 4,
        )
    ));

    Saloon::assertSent(CreateCalendarYearRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(7);
});
