<img src="https://banners.beyondco.de/Laravel%20Bexio.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-bexio&pattern=circuitBoard&style=style_2&description=A+Laravel+Bexio+integration.&md=1&showWatermark=1&fontSize=150px&images=home&widths=500&heights=500">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![run-tests](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml)

This package was developed to give you a quick start to the Bexio API.

## 💡 What is Bexio?

Bexio is a cloud-based simple business software for the self-employed, small businesses and startups.

## 🛠 Requirements

| Package 	 | PHP 	 | Laravel 	      |
|-----------|-------|----------------|
| >v1.0     | >8.2  | > Laravel 10.0 |

## Authentication

The currently supported authentication methods are:

| Method 	  | Supported 	 |
|-----------|:-----------:|
| API token |      ✅      |

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-bexio
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="CodebarAg\Bexio\BexioServiceProvider" --tag="config"
```

You can add the following env variables to your `.env` file:

```dotenv
BEXIO_API_TOKEN= # Your Bexio API token
```

You can retrieve your API token from
your [Bexio Dashboard](https://office.bexio.com/index.php/admin/apiTokens)

## Usage

To use the package, you need to create a BexioConnector instance.

```php
use CodebarAg\Bexio\BexioConnector;
...

$connector = new BexioConnector();
````

### Requests

The following requests are currently supported:

| Request Groups 	  | Supported 	 |
|-------------------|:-----------:|
| Contacts          |      ✅      |
| Contact Relations |      ✅      |
| Contact Groups    |      ✅      |
| Contact Sectors   |      ✅      |
| Addresses         |      ❌      |
| Salutations       |      ❌      |
| Titles            |      ❌      |
| Company Profile   |      ❌      |
| Notes             |      ❌      |
| Files             |      ❌      |
| Bank Accounts     |      ✅      |
| IBAN Payments     |      ❌      |
| QR Payments       |      ❌      |
| Accounts          |      ✅      |
| Account Group     |      ✅      |
| Calendar Years    |      ❌      |
| Business Year     |      ❌      |
| Currencies        |      ✅      |
| Manual Entries    |      ✅      |
| Reports           |      ❌      |
| Taxes             |      ✅      |
| VAT Periods       |      ❌      |

### Responses

The following responses are currently supported for retrieving the response body:

| Response Methods	 | Description                                                                                                                        | Supported 	 |
|-------------------|------------------------------------------------------------------------------------------------------------------------------------|:-----------:|
| body              | Returns the HTTP body as a string                                                                                                  |      ✅      |
| json              | Retrieves a JSON response body and json_decodes it into an array.                                                                  |      ✅      |
| object            | Retrieves a JSON response body and json_decodes it into an object.                                                                 |      ✅      |
| collect           | Retrieves a JSON response body and json_decodes it into a Laravel collection. **Requires illuminate/collections to be installed.** |      ✅      |
| dto               | Converts the response into a data-transfer object. You must define your DTO first                                                  |      ✅      |

See https://docs.saloon.dev/the-basics/responses for more information.

### Enums

We provide enums for the following values:

| Enum 	 | Values 	 |
|--------|:--------:|
| N/A    |   N/A    |

`Note: When using the dto method on a response, the enum values will be converted to their respective enum class.`

### DTOs

We provide DTOs for the following:

| DTO 	                       |
|-----------------------------|
| AccountGroupDTO             |
| AccountDTO                  |
| BankAccountDTO              |
| ContactAdditionalAddressDTO |
| ContactGroupDTO             |
| ContactRelationDTO          |
| ContactDTO                  |
| ContactSectorDTO            |
| CurrencyDTO                 |
| ExchangeCurrencyDTO         |
| ManualEntryDTO              |
| EntryDTO                    |
| FileDTO                     |

In addition to the above, we also provide DTOs to be used for create and edit request for the following:

| DTO 	                                 |
|---------------------------------------|
| CreateEditContactAdditionalAddressDTO |
| CreateEditContactGroupDTO             |
| CreateEditContactRelationDTO          |
| CreateEditContactDTO                  |
| CreateCurrencyDTO                     |
| EditCurrencyDTO                       |
| AddFileDTO                            |
| CreateManualEntryDTO                  |

`Note: This is the prefered method of interfacing with Requests and Responses however you can still use the json, object and collect methods. and pass arrays to the requests.`

### Examples

```php
use CodebarAg\DocuWare\BexioConnector;

// You can either set the token in the constructor or in the .env file

// PROVIDE TOKEN IN CONSTRUCTOR
$connector = new BexioConnector(token: 'your-token');
 
// OR
 
// PROVIDE TOKEN IN .ENV FILE
$connector = new BexioConnector();


/**
 * Fetch A List Of Account Groups
 */
$accountGroups = $connector->send(new FetchAListOfAccountGroupsRequest())->dto();

/**
 * Fetch A List Of Accounts
 */
$accounts = $connector->send(new FetchAListOfAccountsRequest())->dto();

/**
 * Search Accounts
 */
$accounts = $connector->send(new SearchAccountsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Fetch A List Of Bank Accounts
 */
$bankAccounts = $connector->send(new FetchAListOfBankAccountsRequest())->dto();

/**
 * Fetch A Single Bank Account
 */
$bankAccount = $connector->send(new FetchASingleBankAccountRequest(
    id: 1
))->dto();

/**
 * Fetch A List Of Contact Additional Addresses
 */
$contactAdditionalAddresses = $connector->send(new FetchAListOfContactAdditionalAddressesRequest(
    contactId: 1
))->dto();

/**
 * Fetch A Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new FetchAContactAdditionalAddressRequest(
    contactId: 1,
    id: 1
))->dto();

/**
 * Search Contact Additional Address
 */
$contactAdditionalAddresses = $connector->send(new SearchContactAdditionalAddressesRequest(
    contactId: 1,
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Create Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new CreateContactAdditionalAddressRequest(
    contactId: 1,
    data: new CreateEditContactAdditionalAddressDTO(
        name: 'Test',
        subject: 'Test Subject',
        description: 'This is a test',
        address: 'Test Address',
        postcode: '1234',
        city: 'Test City',
    )
));

/**
 * Edit Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new EditAContactAdditionalAddressRequest(
    contactId: 1,
    id: 9,
    data: new CreateEditContactAdditionalAddressDTO(
        name: 'Test Edit',
        subject: 'Test Subject Edit',
        description: 'This is a test edit',
        address: 'Test Address Edit',
        postcode: '4567',
        city: 'Test City Edit',
    )
));

/**
 * Delete Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new DeleteAContactAdditionalAddressRequest(
    contactId: 1,
    id: 9,
));

/**
* Fetch A List Of Contacts
 */
$contacts = $connector->send(new FetchAListOfContactsRequest())->dto();

/**
 * Fetch A Contact
 */
$contact = $connector->send(new FetchAContactRequest(
    id: 1
))->dto();

/**
 * Search Contacts
 */
$contacts = $connector->send(new SearchContactsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Create Contact
 */
$contact = $connector->send(new CreateContactRequest(
    data: new CreateEditContactDTO(
        user_id: 1,
        owner_id: 1,
        contact_type_id: 1,
        name_1: 'Name'
    )
));

/**
 * Bulk Create Contacts
 */
$contact = $connector->send(new BulkCreateContactsRequest(
    data: [
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'Name'
        ),
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'Name 2'
        )
    ]
));

/**
 * Edit Contact
 */
$contact = $connector->send(new EditAContactRequest(
    id: 1,
    data: new CreateEditContactDTO(
        user_id: 1,
        owner_id: 1,
        contact_type_id: 1,
        name_1: 'Name'
    )
));

/**
 * Delete Contact
 */
$contact = $connector->send(new DeleteAContactRequest(
    id: 1
));

/**
 * Restore Contact
 */
$contact = $connector->send(new RestoreAContactRequest(
    id: 1
));

/**
 * Fetch A List Of Contact Groups
 */
$contactGroups = $connector->send(new FetchAListOfContactGroupsRequest())->dto();

/**
 * Fetch A Contact Group
 */
$contactGroup = $connector->send(new FetchAContactGroupRequest(
    id: 1
))->dto();

/**
 * Search Contact Groups
 */
$contactGroups = $connector->send(new SearchContactGroupsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Create Contact Group
 */
$contactGroup = $connector->send(new CreateContactGroupRequest(
    data: new CreateEditContactGroupDTO(
        name: 'Name'
    )
));

/**
 * Edit Contact Group
 */
$contactGroup = $connector->send(new EditAContactGroupRequest(
    id: 1,
    data: new CreateEditContactGroupDTO(
        name: 'Name'
    )
));

/**
 * Delete Contact Group
 */
$contactGroup = $connector->send(new DeleteAContactGroupRequest(
    id: 1
));

/**
 * Fetch A List Of Contact Relations
 */
$contactRelations = $connector->send(new FetchAListOfContactRelationsRequest())->dto();

/**
 * Fetch A Contact Relation
 */
$contactRelation = $connector->send(new FetchAContactRelationRequest(
    id: 1
))->dto();

/**
 * Search Contact Relations
 */
$contactRelations = $connector->send(new SearchContactRelationsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Create Contact Relation
 */
$contactRelation = $connector->send(new CreateContactRelationRequest(
    data: new CreateEditContactRelationDTO(
        contact_id: 1,
        contact_sub_id: 2,
        description: 'Something',
    )
));

/**
 * Edit Contact Relation
 */
$contactRelation = $connector->send(new EditAContactRelationRequest(
    id: 1,
    data: new CreateEditContactRelationDTO(
        contact_id: 1,
        contact_sub_id: 2,
        description: 'Something',
    )
));

/**
 * Delete Contact Relation
 */
$contactRelation = $connector->send(new DeleteAContactRelationRequest(
    id: 1
));

/**
 * Fetch A List Of Contact Sectors
 */
$contactSectors = $connector->send(new FetchAListOfContactSectorsRequest())->dto();

/**
 * Search Contact Sectors
 */
$contactSectors = $connector->send(new SearchContactSectorsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

/**
 * Fetch A List Of Currencies
 */
$currencies = $connector->send(new FetchAListOfCurrenciesRequest())->dto();

/**
 * Fetch A Currency
 */
$currency = $connector->send(new FetchACurrencyRequest(
    id: 1
))->dto();

/**
 * Create Currency
 */
$currency = $connector->send(new CreateCurrencyRequest(
    data: new CreateCurrencyDTO(
        name: 'JPY',
        round_factor: 0.05,
    )
));

/**
 * Edit Currency
 */
$currency = $connector->send(new EditACurrencyRequest(
    id: 1,
    data: new EditCurrencyDTO(
        round_factor: 0.05,
    )
));

/**
 * Delete Currency
 */
$currency = $connector->send(new DeleteACurrencyRequest(
    id: 1
));

/**
 * Fetch All Possible Currency Codes
 */
$currencyCodes = $connector->send(new FetchAllPossibleCurrencyCodesRequest())->dto();

/**
 * Fetch Exchange Rates For Currencies
 */
$exchangeRates = $connector->send(new FetchExchangeRatesForCurrenciesRequest(
    currencyId: 1
))->dto();

/**
 * Fetch A List Of Manual Entries
 */
$manualEntries = $connector->send(new FetchAListOfManualEntriesRequest())->dto();

/**
 * Fetch Files Of Accounting Entry
 */
$files = $connector->send(new FetchFilesOfAccountingEntryRequest(
    manual_entry_id: 1,
    entry_id: 1
))->dto();

/**
 * Fetch File Of Accounting Entry Line
 */
$file = $connector->send(new FetchFileOfAccountingEntryLineRequest(
    manual_entry_id: 1,
    entry_id: 1,
    line_id: 1
))->dto();

/**
 * Create Manual Entry
 */
$manualEntry = $connector->send(new CreateManualEntryRequest(
    data: new CreateManualEntryDTO(
        type: 'manual_single_entry',
        date: '2023-12-13',
        reference_nr: '1234',
        entries: collect([
            new CreateEntryDTO(
                debit_account_id: 89,
                credit_account_id: 90,
                tax_id: 10,
                tax_account_id: 89,
                description: 'Something',
                amount: 100,
                currency_id: 1,
                currency_factor: 1,
            ),
        ]),
    )
));

/**
 * Add File To Accounting Entry Line
 */
$manualEntry = $connector->send(new AddFileToAccountingEntryLineRequest(
    manual_entry_id: 1,
    entry_id: 1,
    data: new AddFileDTO(
        name: 'fileName',
        absolute_file_path_or_stream: fopen('image.png', 'r'),
        filename: 'image.png',
    )
));

/**
 * Get Next Reference Number
 */
$referenceNumber = $connector->send(new GetNextReferenceNumberRequest())->dto();

/**
 * Fetch A List Of Taxes
 */
$taxes = $connector->send(new FetchAListOfTaxesRequest())->dto();

/**
 * Fetch A Tax
 */
$tax = $connector->send(new FetchATaxRequest(
    id: 1
))->dto();

/**
 * Delete A Tax
 */
$tax = $connector->send(new DeleteATaxRequest(
    id: 1
));
```

####

## 🚧 Testing

Copy your own phpunit.xml-file.

```bash
cp phpunit.xml.dist phpunit.xml
```

Run the tests:

```bash
./vendor/bin/pest
```

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## ✏️ Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

```bash
composer test
```

### Code Style

```bash
./vendor/bin/pint
```

## 🧑‍💻 Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on reporting security vulnerabilities.

## 🙏 Credits

- [Rhys Lees](https://github.com/RhysLees)
- [Sebastian Fix](https://github.com/StanBarrows)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)
- [Laravel Saloon by Sam Carré](https://github.com/Sammyjo20/Saloon)

## 🎭 License

The MIT License (MIT). Please have a look at [License File](LICENSE.md) for more information.
