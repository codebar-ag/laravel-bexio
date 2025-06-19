<img src="https://banners.beyondco.de/Laravel%20Bexio.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-bexio&pattern=circuitBoard&style=style_2&description=A+Laravel+Bexio+integration.&md=1&showWatermark=0&fontSize=150px&images=home&widths=500&heights=500">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![GitHub-Tests](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml)
[![GitHub Code Style](https://github.com/codebar-ag/laravel-bexio/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml)
[![Dependency Review](https://github.com/codebar-ag/laravel-bexio/actions/workflows/dependency-review.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/dependency-review.yml)

This package was developed to give you a quick start to the Bexio API.

## 💡 What is Bexio?

Bexio is a cloud-based simple business software for the self-employed, small businesses and startups.

## 🛠 Requirements

| Package | PHP         | Laravel |
| ------- | ----------- | ------- |
| v12.0.0 | ^8.2 - ^8.4 | 12.x    |
| v11.0.0 | ^8.2 - ^8.3 | 11.x    |
| v1.0.0  | ^8.2        | 10.x    |

## Authentication

The currently supported authentication methods are:

| Method    | Supported |
| --------- | :-------: |
| API token |    ✅     |
| OAuth     |    ✅     |

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-bexio
```

### 🔧 Configuration

Publish the config file to customize authentication settings:

```bash
php artisan vendor:publish --provider="CodebarAg\Bexio\BexioServiceProvider" --tag=bexio-config
```

Optionally, you may also publish the view:

```bash
php artisan vendor:publish --provider="CodebarAg\Bexio\BexioServiceProvider" --tag=bexio-views
```

Add the following variables to your `.env` file as needed:

```dotenv
BEXIO_API_TOKEN= # Your Bexio API token (for PAT)
BEXIO_USE_OAUTH2=true # Set to true to use OAuth2 (default: false)
BEXIO_CLIENT_ID= # Your Bexio Client ID (for OAuth2)
BEXIO_CLIENT_SECRET= # Your Bexio Client Secret (for OAuth2)
BEXIO_ALLOWED_EMAILS= # The email addresses for the Bexio accounts that are used to authorize the application (for OAuth2)
```

> **Note:**
> You only need to set either `BEXIO_API_TOKEN` (for Personal Access Token authentication) **or** the OAuth2 environment variables (`BEXIO_CLIENT_ID`, `BEXIO_CLIENT_SECRET`, `BEXIO_ALLOWED_EMAILS`, etc.)—not both.
>
> -   If `BEXIO_USE_OAUTH2=true`, the package will use OAuth2 and ignore `BEXIO_API_TOKEN`.
> -   If `BEXIO_USE_OAUTH2=false` (or unset), the package will use the API token and ignore the OAuth2 environment variables.

You can create and retrieve either:

-   An **API Token** (Personal Access Token), or
-   A **Client ID / Client Secret** for OAuth2

from your [Bexio Developer Dashboard](https://developer.bexio.com).

## 🔐 OAuth2

To use OAuth2, set `BEXIO_USE_OAUTH2=true` and ensure all relevant environment variables are configured.

### OAuth2 Flow

1. User visits `/bexio/oauth/redirect` to start the flow.
2. After authenticating with Bexio, the user is redirected to `/bexio/oauth/callback`.
3. The callback handler exchanges the authorization code for an access and refresh token.
4. Tokens are securely stored in cache and used for subsequent API requests.

> ⚠️ **Refresh Token Expiry Notice**  
> Refresh tokens do not have a fixed expiration, but they are tied to an _offline session_ that expires after **1 year of inactivity**.  
> You must refresh the token at least once a year to avoid requiring reauthorization.

📖 For details, see the [Bexio API Docs on Authorization](https://docs.bexio.com/#section/Authentication).

---

### 🛂 Scopes

When using OAuth2, you **must explicitly request the scopes** you need. These control which API endpoints your access token can use.

Define scopes in your `config/bexio.php`:

```php
'auth' => [
    'scopes' => ['contact_edit', 'kb_invoice_show'],
],
```

> ℹ️ Some scopes imply others. For example, `contact_edit` also grants `contact_show`.

🔗 [See the full list of scopes in the Bexio API Docs](https://docs.bexio.com/#section/Authentication/API-Scopes)

---

### ✅ Required Scopes for Package

These OpenID Connect scopes are always applied by the package:

-   `openid`
-   `offline_access`
-   `email`

These are required to:

-   Verify the authorized email from Bexio
-   Enable token refresh
-   Retrieve identity claims

---

### 🚦 OAuth2 Routes

| Route                 | Description                |
| --------------------- | -------------------------- |
| /bexio/oauth/redirect | Start OAuth2 authorization |
| /bexio/oauth/callback | Handle OAuth2 callback     |

**Note:** The `route_prefix` config option allows you to change the base URI for all Bexio package routes (default is `/bexio`).
For most applications, you should leave this setting as-is. Only change it if you need to avoid a route conflict or require a custom URL structure.

---

### 💾 Token Storage

OAuth2 tokens are cached (encrypted) via the `BexioOAuthTokenStore` class, which uses Laravel's cache and encryption facilities by default.

You can customize the cache key prefix by setting `BEXIO_CACHE_PREFIX` in your `.env` or in `config/bexio.php`.

---

### 🧰 Full Configuration Example

After publishing the config file, you can customize values in `config/bexio.php`:

```php
return [
    'cache_prefix' => env('BEXIO_CACHE_PREFIX', 'bexio_oauth_'),

    'auth' => [
        'use_oauth2' => env('BEXIO_USE_OAUTH2', false),
        'token' => env('BEXIO_API_TOKEN'),
        'oauth2' => [
            'client_id' => env('BEXIO_CLIENT_ID'),
            'client_secret' => env('BEXIO_CLIENT_SECRET'),
            'allowed_emails' => array_filter(array_map('trim', explode(',', env('BEXIO_ALLOWED_EMAILS', '')))),
            'scopes' => [],
        ],
    ],

    'route_prefix' => 'bexio',
];
```

---

### 🏢 Dynamic Configuration

For advanced use cases (multi-tenancy, per-request config, runtime customization), you can bind your own config resolver in any service provider (such as `AppServiceProvider`).

This enables dynamic credentials per request, tenant, or user—without modifying package code or routes.

#### 🛠️ Example: Adding a Config Resolver

See below for examples of how to customize the config resolver in your own service provider (such as `AppServiceProvider`).

#### Basic Example

```php
use Illuminate\Http\Request;
use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;

public function register(): void
{
    $this->app->bind('bexio.config.resolver', fn() => fn(Request $request) => new ConfigWithCredentials(
        clientId: env('BEXIO_CLIENT_ID'),
        clientSecret: env('BEXIO_CLIENT_SECRET'),
        scopes: ['contact_show', 'contact_edit', 'article_show', 'article_edit'],
    ));
}
```

#### Multi-Tenant Example

```php
use Illuminate\Http\Request;
use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;

public function register(): void
{
    $this->app->bind('bexio.config.resolver', fn() => function (Request $request) {
        $tenant = $request->user()?->tenant ?? null;
        return new ConfigWithCredentials(
            clientId: $tenant?->bexio_client_id ?? env('BEXIO_CLIENT_ID'),
            clientSecret: $tenant?->bexio_client_secret ?? env('BEXIO_CLIENT_SECRET'),
            scopes: $tenant?->bexio_scopes ?? ['contact_show', 'contact_edit'],
        );
    });
}
```

-   The closure receives the current request, so you can use any logic (user, tenant, domain, etc).
-   If not bound, the package falls back to static config in `config/bexio.php`.

> **How It Works:**
> The package will always use your dynamic config resolver if it is bound.  
> If not, it falls back to `config/bexio.php` or the values you pass directly to the connector.

---

### 🔄 Migrating from PAT to OAuth2

To switch from a Personal Access Token (PAT) to OAuth2 authentication:

1. **Update your `.env` file:**

    ```dotenv
    BEXIO_USE_OAUTH2=true
    BEXIO_CLIENT_ID=your-client-id
    BEXIO_CLIENT_SECRET=your-client-secret
    BEXIO_ALLOWED_EMAILS=your-verified-bexio-email1,your-verified-bexio-email2
    ```

    > ℹ️ You can leave `BEXIO_API_TOKEN` blank or remove it. Only one method needs to be active — if `BEXIO_USE_OAUTH2=true` and `BEXIO_API_TOKEN` is set, it will be ignored.

2. **Publish config (if not already done):**

    ```bash
    php artisan vendor:publish --tag=bexio-config
    ```

3. **Configure OAuth2 scopes:**

    Define the scopes your app needs in `config/bexio.php`.

    See [Scopes](#🛂-scopes) for details and examples.

4. **(Optional) Customize the view:**

    ```bash
    php artisan vendor:publish --tag=bexio-views
    ```

5. **Clear configuration and cache:**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

6. **Start the OAuth2 flow:**

    Visit `/bexio/oauth/redirect` in your browser to authorize the app and store tokens.

## Usage

To use the package, you need to create a `BexioConnector` instance. There are three idiomatic ways to configure the connector:

1. **Static config (default):**
   Use values from `config/bexio.php` (suitable for most single-tenant apps):

    ```php
    use CodebarAg\Bexio\BexioConnector;

    $connector = new BexioConnector();
    ```

2. **Dynamic config (per-request or multi-tenant):**
   Bind a resolver as shown in [Dynamic Configuration](#🏢-dynamic-configuration) in any service provider. The connector will use the config returned by your resolver automatically:

    ```php
    // In your service provider (see above for full example)
    $this->app->bind('bexio.config.resolver', fn() => function ($request) {
        // ... return ConfigWithCredentials instance
    });

    // Usage
    $connector = new BexioConnector();
    ```

3. **Direct instantiation:**
   Pass a `ConfigWithCredentials` DTO directly when creating the connector:

    ```php
    use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;
    use CodebarAg\Bexio\BexioConnector;

    $connector = new BexioConnector(
        configuration: new ConfigWithCredentials(
            clientId: '...',
            clientSecret: '...',
        )
    );
    ```

### Responses

The following responses are currently supported for retrieving the response body:

| Response Methods | Description                                                                                                                        | Supported |
| ---------------- | ---------------------------------------------------------------------------------------------------------------------------------- | :-------: |
| body             | Returns the HTTP body as a string                                                                                                  |    ✅     |
| json             | Retrieves a JSON response body and json_decodes it into an array.                                                                  |    ✅     |
| object           | Retrieves a JSON response body and json_decodes it into an object.                                                                 |    ✅     |
| collect          | Retrieves a JSON response body and json_decodes it into a Laravel collection. **Requires illuminate/collections to be installed.** |    ✅     |
| dto              | Converts the response into a data-transfer object. You must define your DTO first                                                  |    ✅     |

See https://docs.saloon.dev/the-basics/responses for more information.

### Enums

We provide enums for the following values:

| Enum                                   | Values                                                                                                                                                                                                                                                          |
| -------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Accounts: SearchFieldEnum              | ACCOUNT_NO(), self FIBU_ACCOUNT_GROUP_ID(), NAME(), ACCOUNT_TYPE()                                                                                                                                                                                              |
| Accounts: AccountTypeEnum              | EARNINGS(), EXPENDITURES(), ACTIVE_ACCOUNTS(), PASSIVE_ACCOUNTS(), COMPLETE_ACCOUNTS()                                                                                                                                                                          |
| AdditionalAddresses: AddSearchTypeEnum | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| CalendarYears: VatAccountingMethodEnum | EFFECTIVE(), NET_TAX()                                                                                                                                                                                                                                          |
| CalendarYears: VatAccountingTypeEnum   | AGREED(), COLLECTED()                                                                                                                                                                                                                                           |
| ContactGroups: OrderByEnum             | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| ContactRelations: OrderByEnum          | ID(), ID_ASC(), ID_DESC(), CONTACT_ID(), CONTACT_ID_ASC(), CONTACT_ID_DESC(), CONTACT_SUB_ID(), CONTACT_SUB_ID_ASC(), CONTACT_SUB_ID_DESC(), UPDATED_AT(), UPDATED_AT_ASC(), UPDATED_AT_DESC()                                                                  |
| Contacts: OrderByEnum                  | ID(), ID_ASC(), ID_DESC(), NR(), NR_ASC(), NR_DESC(), NAME_1(), NAME_1_ASC(), NAME_1_DESC(), UPDATED_AT(), UPDATED_AT_ASC(), UPDATED_AT_DESC()                                                                                                                  |
| ContactSectors: OrderByEnum            | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| IbanPayments: AllowanceTypeEnum        | FEE_PAID_BY_SENDER(), FEE_PAID_BY_RECIPIENT(), FEE_SPLIT(), NO_FEE()                                                                                                                                                                                            |
| IbanPayments: StatusEnum               | OPEN(), TRANSFERRED(), DOWNLOADED(), ERROR(), CANCELLED()                                                                                                                                                                                                       |
| ManualEntries: TypeEnum                | MANUAL_SINGLE_ENTRY(), MANUAL_GROUP_ENTRY(), MANUAL_COMPOUND_ENTRY()                                                                                                                                                                                            |
| QrPayments: AllowanceTypeEnum          | FEE_PAID_BY_SENDER(), FEE_PAID_BY_RECIPIENT(), FEE_SPLIT(), NO_FEE()                                                                                                                                                                                            |
| QrPayments: StatusEnum                 | OPEN(), TRANSFERRED(), DOWNLOADED(), ERROR(), CANCELLED()                                                                                                                                                                                                       |
| Taxes: ScopeEnum                       | ACTIVE(), INACTIVE()                                                                                                                                                                                                                                            |
| Taxes: TypeEnum                        | SALES_TAX(), PRE_TAX()                                                                                                                                                                                                                                          |
| Titles: OrderByEnum                    | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| SearchCriteriaEnum                     | EQUALS(), DOUBLE_EQUALS(), EQUAL(), NOT_EQUALS(), GREATER_THAN_SYMBOL(), GREATER_THAN(), GREATER_EQUAL_SYMBOL(), GREATER_EQUAL(), LESS_THAN_SYMBOL(), LESS_THAN(), LESS_EQUAL_SYMBOL(), LESS_EQUAL(), LIKE(), NOT_LIKE(), IS_NULL(), NOT_NULL(), IN(), NOT_IN() |

`Note: When using the dto method on a response, the enum values will be converted to their respective enum class.`

### DTOs

We provide DTOs for the following:

| DTO                         |
| --------------------------- |
| AccountGroupDTO             |
| AccountDTO                  |
| BankAccountDTO              |
| AdditionalAddressDTO        |
| BankAccountDTO              |
| BusinessActivityDTO         |
| BusinessYearDTO             |
| CalendarYearDTO             |
| CompanyProfileDTO           |
| ContactAdditionalAddressDTO |
| ContactGroupDTO             |
| ContactRelationDTO          |
| ContactDTO                  |
| CreateEditContactDTO        |
| ContactSectorDTO            |
| CurrencyDTO                 |
| CreateCurrencyDTO           |
| EditCurrencyDTO             |
| ExchangeCurrencyDTO         |
| DocumentSettingDTO          |
| FileDTO                     |
| EditFileDTO                 |
| FileUsageDTO                |
| InvoiceDTO                  |
| InvoicePositionDTO          |
| InvoiceTaxDTO               |
| PdfDTO                      |
| LanguageDTO                 |
| AddFileDTO                  |
| EntryDTO                    |
| FileDTO                     |
| ManualEntryDTO              |
| NoteDTO                     |
| PaymentDTO                  |
| PaymentTypeDTO              |
| ProjectDTO                  |
| JournalDTO                  |
| SalutationDTO               |
| TaxDTO                      |
| TitleDTO                    |
| UnitDTO                     |
| UserDTO                     |
| UserinfoDTO                 |
| VatPeriodDTO                |

In addition to the above, we also provide DTOs to be used for create and edit request for the following:

| DTO                                   |
| ------------------------------------- |
| CreateCalendarYearDTO                 |
| CreateEditAdditionalAddressDTO        |
| CreateEditContactAdditionalAddressDTO |
| CreateEditContactGroupDTO             |
| CreateEditContactRelationDTO          |
| CreateEditContactDTO                  |
| CreateCurrencyDTO                     |
| EditCurrencyDTO                       |
| EditFileDTO                           |
| AddFileDTO                            |
| CreateEditIbanPaymentDTO              |
| CreateEntryDTO                        |
| CreateManualEntryDTO                  |
| CreateEditNoteDTO                     |
| CreateEditQrPaymentDTO                |
| CreateEditSalutationDTO               |
| CreateEditTitleDTO                    |

`Note: This is the prefered method of interfacing with Requests and Responses however you can still use the json, object and collect methods. and pass arrays to the requests.`

### Examples

```php
use CodebarAg\bexio\BexioConnector;

// === PAT (Personal Access Token) Authentication ===
// You can either set the token in the constructor or in the .env file

// PROVIDE TOKEN IN CONSTRUCTOR
$connector = new BexioConnector(token: 'your-token');

// OR

// PROVIDE TOKEN IN .ENV FILE
$connector = new BexioConnector();

// === OAuth2 Authentication ===
// If you have configured OAuth2 in your .env and config/bexio.php,
// the connector will automatically use the cached OAuth2 token
// after completing the authorization flow via the provided routes.

// Example (after OAuth2 flow is complete):
$connector = new BexioConnector();
// No token parameter needed; uses OAuth2 credentials from cache
```

### Accounts

```php
/**
 * Fetch A List Of Account Groups
 */
$accountGroups = $connector->send(new FetchAListOfAccountGroupsRequest())->dto();
```

```php
/**
 * Fetch A List Of Accounts
 */
$accounts = $connector->send(new FetchAListOfAccountsRequest())->dto();
```

```php
/**
 * Search Accounts
 */
$accounts = $connector->send(new SearchAccountsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

### Addresses

```php
/**
 * Fetch A List Of Addresses
 */
$addresses = $connector->send(new FetchAListOfAddressesRequest())->dto();
```

```php
/**
 * Fetch An Address
 */
$address = $connector->send(new FetchAnAddressRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Addresses
 */
$addresses = $connector->send(new SearchAddressesRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Address
 */
$address = $connector->send(new CreateAddressRequest(
    data: new CreateEditAddressDTO(
        name: 'Test',
        subject: 'Test Subject',
        description: 'This is a test',
        address: 'Test Address',
        postcode: '1234',
        city: 'Test City',
    )
));
```

```php
/**
 * Edit Address
 */
$address = $connector->send(new EditAnAddressRequest(
    id: 1,
    data: new CreateEditAddressDTO(
        name: 'Test Edit',
        subject: 'Test Subject Edit',
        description: 'This is a test edit',
        address: 'Test Address Edit',
        postcode: '4567',
        city: 'Test City Edit',
    )
));
```

```php
/**
 * Delete Address
 */
$address = $connector->send(new DeleteAnAddressRequest(
    id: 1
));
```

### Bank Accounts

```php
/**
 * Fetch A List Of Bank Accounts
 */
$bankAccounts = $connector->send(new FetchAListOfBankAccountsRequest())->dto();
```

```php
/**
 * Fetch A Single Bank Account
 */
$bankAccount = $connector->send(new FetchASingleBankAccountRequest(
    id: 1
))->dto();
```

### Business Years

```php
/**
 * Fetch A List Of Business Years
 */
$businessYears = $connector->send(new FetchAListOfBusinessYearsRequest())->dto();
```

```php
/**
 * Fetch A Business Year
 */
$businessYear = $connector->send(new FetchABusinessYearRequest(
    id: 1
))->dto();
```

### Calendar Years

```php
/**
 * Fetch A List Of Calendar Years
 */
$calendarYears = $connector->send(new FetchAListOfCalendarYearsRequest())->dto();
```

```php
/**
 * Fetch A Calendar Year
 */
$calendarYear = $connector->send(new FetchACalendarYearRequest(
    id: 1
))->dto();
```

### Company Profiles

```php
/**
 * Fetch A List Of Company Profiles
 */
$companyProfiles = $connector->send(new FetchAListOfCompanyProfilesRequest())->dto();
```

```php
/**
 * Fetch A Company Profile
 */
$companyProfile = $connector->send(new FetchACompanyProfileRequest(
    id: 1
))->dto();
```

### Additional Addresses

```php
/**
 * Fetch A List Of Contact Additional Addresses
 */
$contactAdditionalAddresses = $connector->send(new FetchAListOfContactAdditionalAddressesRequest(
    contactId: 1
))->dto();
```

```php
/**
 * Fetch A Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new FetchAContactAdditionalAddressRequest(
    contactId: 1,
    id: 1
))->dto();
```

```php
/**
 * Search Contact Additional Address
 */
$contactAdditionalAddresses = $connector->send(new SearchContactAdditionalAddressesRequest(
    contactId: 1,
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
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
```

```php
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
```

```php
/**
 * Delete Contact Additional Address
 */
$contactAdditionalAddress = $connector->send(new DeleteAContactAdditionalAddressRequest(
    contactId: 1,
    id: 9,
));
```

### Contact Groups

```php
/**
 * Fetch A List Of Contact Groups
 */
$contactGroups = $connector->send(new FetchAListOfContactGroupsRequest())->dto();
```

```php
/**
 * Fetch A Contact Group
 */
$contactGroup = $connector->send(new FetchAContactGroupRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Contact Groups
 */
$contactGroups = $connector->send(new SearchContactGroupsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Contact Group
 */
$contactGroup = $connector->send(new CreateContactGroupRequest(
    data: new CreateEditContactGroupDTO(
        name: 'Name'
    )
));
```

```php
/**
 * Edit Contact Group
 */
$contactGroup = $connector->send(new EditAContactGroupRequest(
    id: 1,
    data: new CreateEditContactGroupDTO(
        name: 'Name'
    )
));
```

```php
/**
 * Delete Contact Group
 */
$contactGroup = $connector->send(new DeleteAContactGroupRequest(
    id: 1
));
```

### Contact Relations

```php
/**
 * Fetch A List Of Contact Relations
 */
$contactRelations = $connector->send(new FetchAListOfContactRelationsRequest())->dto();
```

```php
/**
 * Fetch A Contact Relation
 */
$contactRelation = $connector->send(new FetchAContactRelationRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Contact Relations
 */
$contactRelations = $connector->send(new SearchContactRelationsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
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
```

```php
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
```

```php
/**
 * Delete Contact Relation
 */
$contactRelation = $connector->send(new DeleteAContactRelationRequest(
    id: 1
));
```

### Contacts

```php
/**
* Fetch A List Of Contacts
 */
$contacts = $connector->send(new FetchAListOfContactsRequest())->dto();
```

```php
/**
 * Fetch A Contact
 */
$contact = $connector->send(new FetchAContactRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Contacts
 */
$contacts = $connector->send(new SearchContactsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
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
```

```php
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
```

```php
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
```

```php
/**
 * Delete Contact
 */
$contact = $connector->send(new DeleteAContactRequest(
    id: 1
));
```

```php
/**
 * Restore Contact
 */
$contact = $connector->send(new RestoreAContactRequest(
    id: 1
));
```

### Contact Sectors

```php
/**
 * Fetch A List Of Contact Sectors
 */
$contactSectors = $connector->send(new FetchAListOfContactSectorsRequest())->dto();
```

```php
/**
 * Search Contact Sectors
 */
$contactSectors = $connector->send(new SearchContactSectorsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();

```

### Currencies

```php
/**
 * Fetch A List Of Currencies
 */
$currencies = $connector->send(new FetchAListOfCurrenciesRequest())->dto();
```

```php
/**
 * Fetch A Currency
 */
$currency = $connector->send(new FetchACurrencyRequest(
    id: 1
))->dto();
```

```php
/**
 * Create Currency
 */
$currency = $connector->send(new CreateCurrencyRequest(
    data: new CreateCurrencyDTO(
        name: 'JPY',
        round_factor: 0.05,
    )
));
```

```php
/**
 * Edit Currency
 */
$currency = $connector->send(new EditACurrencyRequest(
    id: 1,
    data: new EditCurrencyDTO(
        round_factor: 0.05,
    )
));
```

```php
/**
 * Delete Currency
 */
$currency = $connector->send(new DeleteACurrencyRequest(
    id: 1
));
```

```php
/**
 * Fetch All Possible Currency Codes
 */
$currencyCodes = $connector->send(new FetchAllPossibleCurrencyCodesRequest())->dto();
```

```php
/**
 * Fetch Exchange Rates For Currencies
 */
$exchangeRates = $connector->send(new FetchExchangeRatesForCurrenciesRequest(
    currencyId: 1
))->dto();
```

### Files

```php
/**
 * Fetch A List Of Files
 */
$files = $connector->send(new FetchAListOfFilesRequest())->dto();
```

```php
/**
 * Get A Single File
 */
$file = $connector->send(new GetASingleFileRequest(
    id: 1
))->dto();
```

```php
/**
 * Show A File Usage
 */
$fileUsage = $connector->send(new ShowAFileUsageRequest(
    id: 1
))->dto();
```

```php
/**
 * Get A File Preview
 */
$filePreview = $connector->send(new GetAFilePreviewRequest(
    id: 1
))->stream();
```

```php
/**
 * Download File Download
 */
$fileDownload = $connector->send(new DownloadFileDownloadRequest(
    id: 1
))->stream();
```

```php
/**
 * Create A File
 */
$file = $connector->send(new CreateAFileRequest(
    data: [
        new MultipartValue(
            name: 'picture',
            value: fopen(__DIR__ . 'image.png', 'r'),
        )
    ],
));
```

```php
/**
 * Edit A File
 */
$file = $connector->send(new EditAFileRequest(
    id: 1,
    data: new EditFileDTO(
        name: 'Test name edited',
        is_archived: false,
        source_type: 'web',
    )
));
```

```php
/**
 * Delete A File
 */
$file = $connector->send(new DeleteAFileRequest(
    id: 1
));
```

### Iban Payments

```php
/**
 * Fetch An Iban Payment
 */
$payment = $connector->send(new GetIbanPaymentRequest(
    bank_account_id: 1,
    payment_id: 3
))->dto();
```

```php
/**
 * Create Iban Payment
 */
$payment = $connector->send(new CreateIbanPaymentRequest(
    bank_account_id: 1,
    data: new CreateEditIbanPaymentDTO(
        instructed_amount: [
            'currency' => 'CHF',
            'amount' => 100,
        ],
        recipient: [
            'name' => 'Müller GmbH',
            'street' => 'Sonnenstrasse',
            'zip' => 8005,
            'city' => 'Zürich',
            'country_code' => 'CH',
            'house_number' => 36,
        ],
        iban: 'CH8100700110005554634',
        execution_date: '2024-01-08',
        is_salary_payment: false,
        is_editing_restricted: false,
        message: 'Rechnung 1234',
        allowance_type: 'no_fee',
    )
))->dto();
```

```php
/**
 * Update Iban Payment
 *
 * NOTE: THE PAYMENT MUST HAVE A STATUS OF OPEN TO BE UPDATED
 */
$payment = $connector->send(new EditIbanPaymentRequest(
    bank_account_id: 1,
    payment_id: 3,
    iban: 'CH8100700110005554634',
    id: 3,
    data: new CreateEditIbanPaymentDTO(
        instructed_amount: [
            'currency' => 'CHF',
            'amount' => 100,
        ],
        recipient: [
            'name' => 'Müller GmbH',
            'street' => 'Colchester Place',
            'zip' => 8005,
            'city' => 'Zürich',
            'country_code' => 'CH',
            'house_number' => 36,
        ],
        iban: 'CH8100700110005554634',
        execution_date: '2024-01-08',
        is_salary_payment: false,
        is_editing_restricted: false,
        message: 'Rechnung 1234',
        allowance_type: 'no_fee',
    )
))->dto();
```

### Invoices

```php
/**
 * Fetch A List Of Invoices
 */
$invoices = $connector->send(new FetchAListOfInvoicesRequest())->dto();
```

```php
/**
 * Fetch An Invoice
 */
$invoice = $connector->send(new FetchAnInvoiceRequest(
    invoice_id: 1
))->dto();
```

```php
/**
 * Create An Invoice
 */
$contacts = $connector->send(new FetchAListOfContactsRequest);
$user = $connector->send(new FetchAuthenticatedUserRequest);
$languages = $connector->send(new FetchAListOfLanguagesRequest);
$banks = $connector->send(new FetchAListOfBankAccountsRequest);
$currencies = $connector->send(new FetchAListOfCurrenciesRequest);
$paymentTypes = $connector->send(new FetchAListOfPaymentTypesRequest);
$units = $connector->send(new FetchAListOfUnitsRequest);
$accounts = $connector->send(new FetchAListOfAccountsRequest);
$taxes = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'));

$newInvoice = InvoiceDTO::fromArray([
    'title' => 'Test',
    'contact_id' => $contacts->dto()->first()->id,
    'user_id' => $user->dto()->id,
    'pr_project_id' => null,
    'language_id' => $languages->dto()->first()->id,
    'bank_account_id' => $banks->dto()->first()->id,
    'currency_id' => $currencies->dto()->first()->id,
    'payment_type_id' => $paymentTypes->dto()->first()->id,
    'mwst_type' => 1,
    'mwst_is_net' => true,
    'show_position_taxes' => true,
    'is_valid_from' => now()->format('Y-m-d h:m:s'),
    'is_valid_to' => now()->addDays(5)->format('Y-m-d h:m:s'),
    'api_reference' => Str::uuid(),
    'positions' => [
        InvoicePositionDTO::fromArray([
            'type' => 'KbPositionText',
            'show_pos_nr' => true,
            'text' => Str::uuid(),
        ]),
        InvoicePositionDTO::fromArray([
            'type' => 'KbPositionCustom',
            'amount' => 1,
            'unit_id' => $units->dto()->first()->id,
            'account_id' => $accounts->dto()->filter(fn ($account) => $account->account_type_enum === AccountTypeEnum::ACTIVE_ACCOUNTS())->first()->id,
            'tax_id' => $taxes->dto()->first()->id,
            'text' => Str::uuid(),
            'unit_price' => 100,
            'discount_in_percent' => '0',
        ]),
    ],
]);

$invoice = $connector->send(new CreateAnInvoiceRequest(invoice: $newInvoice))->dto();
```

```php
/**
 * Edit An Invoice
 */
$editInvoice = $connector->send(new FetchAnInvoiceRequest(invoice_id: 1))->dto();

$editInvoice->title = 'Test Invoice';

$invoice = $connector->send(new EditAnInvoiceRequest(invoice_id: 1, invoice: $editInvoice));
```

```php
/**
 * Delete An Invoice
 */
$response = $connector->send(new DeleteAnInvoiceRequest(
    invoice_id: 1
));
```

```php
/**
 * Cancel An Invoice
 */
$response = $connector->send(new CancelAnInvoiceRequest(
    invoice_id: 1
));
```

```php
/**
 * Create A Default Position For An Invoice
 */
$units = $connector->send(new FetchAListOfUnitsRequest);
$accounts = $connector->send(new FetchAListOfAccountsRequest);
$taxes = $connector->send(new FetchAListOfTaxesRequest(scope: 'active', types: 'sales_tax'));

$position = InvoicePositionDTO::fromArray([
    'type' => 'KbPositionCustom',
    'amount' => 1,
    'unit_id' => $units->dto()->first()->id,
    'account_id' => $accounts->dto()->filter(fn ($account) => $account->account_type === 1)->first()->id,
    'tax_id' => $taxes->dto()->first()->id,
    'text' => Str::uuid(),
    'unit_price' => 100,
    'discount_in_percent' => '0',
]);

$response = $connector->send(new CreateADefaultPositionRequest(
    kb_document_type: 'kb_invoice',
    invoice_id: 1,
    position: $position,
));
```

```php
/**
 * Create A Sub Position For An Invoice
 */
$position = InvoicePositionDTO::fromArray([
    'type' => 'KbSubPosition',
    'text' => Str::uuid(),
    'show_pos_nr' => true,
]);

$response = $connector->send(new CreateASubPositionRequest(
    kb_document_type: 'kb_invoice',
    invoice_id: 1,
    position: $position,
));
```

```php
/**
 * Show PDF
 */
$pdf = $connector->send(new ShowPdfRequest(
    invoice_id: 1
))->dto();

/**
 * Saving PDF from response
 */
Storage::disk('local')->put('your/directory/'. $pdf->name, base64_decode($pdf->content));

/**
 * Download PDF from response
 */
return response(base64_decode($pdf->content))
    ->header('Content-Type', $pdf->mime)
    ->header('Content-Disposition', 'attachment; filename="'.$pdf->name.'"')
    ->header('Content-Length', $pdf->size);
```

### Languages

```php
/**
 * Fetch A List Of Languages
 */
$languages = $connector->send(new FetchAListOfLanguagesRequest())->dto();
```

### Manual Entries

```php
/**
 * Fetch A List Of Manual Entries
 */
$manualEntries = $connector->send(new FetchAListOfManualEntriesRequest())->dto();
```

```php
/**
 * Fetch Files Of Accounting Entry
 */
$files = $connector->send(new FetchFilesOfAccountingEntryRequest(
    manual_entry_id: 1,
    entry_id: 1
))->dto();
```

```php
/**
 * Fetch File Of Accounting Entry Line
 */
$file = $connector->send(new FetchFileOfAccountingEntryLineRequest(
    manual_entry_id: 1,
    entry_id: 1,
    line_id: 1
))->dto();
```

```php
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
```

```php
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
```

```php
/**
 * Get Next Reference Number
 */
$referenceNumber = $connector->send(new GetNextReferenceNumberRequest())->dto();
```

### Notes

```php
/**
 * Fetch A List Of Notes
 */
$notes = $connector->send(new FetchAListOfNotesRequest())->dto();
```

```php
/**
 * Fetch A Note
 */
$note = $connector->send(new FetchANoteRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Notes
 */
$notes = $connector->send(new SearchNotesRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Note
 */
$note = $connector->send(new CreateNoteRequest(
    data: new CreateEditNoteDTO(
        title: 'Test',
        content: 'Test Content',
        is_public: true,
    )
));
```

```php
/**
 * Edit Note
 */
$note = $connector->send(new EditANoteRequest(
    id: 1,
    data: new CreateEditNoteDTO(
        title: 'Test Edit',
        content: 'Test Content Edit',
        is_public: true,
    )
));
```

```php
/**
 * Delete Note
 */
$note = $connector->send(new DeleteANoteRequest(
    id: 1
));
```

### Payments

```php
/**
 * Fetch A List Of Payments
 */
 $payments = $connector->send(new FetchAListOfPaymentsRequest())->dto();
```

```php
/**
* Cancel A Payment
*/
$payment = $connector->send(new CancelAPaymentRequest(
    payment_id: 1
))->dto();
```

```php
/**
* Delete A Payment
*/
$payment = $connector->send(new DeleteAPaymentRequest(
    payment_id: 1
))->json();
```

### Qr Payments

```php
/**
* Fetch A Qr Payment
*/
$payment = $connector->send(new GetQrPaymentRequest(
    bank_account_id: 1,
    payment_id: 4
))->dto();
```

```php
/**
* Create A Qr Payment
*/
$connector->send(new CreateQrPaymentRequest(
    bank_account_id: 1,
    data: new CreateEditQrPaymentDTO(
        instructed_amount: [
            'currency' => 'CHF',
            'amount' => 100,
        ],
        recipient: [
            'name' => 'Müller GmbH',
            'street' => 'Sonnenstrasse',
            'zip' => 8005,
            'city' => 'Zürich',
            'country_code' => 'CH',
            'house_number' => 36,
        ],
        execution_date: '2024-01-08',
        iban: 'CH8100700110005554634',
        qr_reference_nr: null,
        additional_information: null,
        is_editing_restricted: false,
    )
))->dto();
```

```php
/**
* Update A Qr Payment
 *
 * NOTE: THE PAYMENT MUST HAVE A STATUS OF OPEN TO BE UPDATED
*/
$payment = $connector->send(new EditQrPaymentRequest(
    bank_account_id: 1,
    payment_id: 4,
    iban: '8100700110005554634',
    id: 4,
    data: new CreateEditQrPaymentDTO(
        instructed_amount: [
            'currency' => 'CHF',
            'amount' => 100,
        ],
        recipient: [
            'name' => 'Müller GmbH',
            'street' => 'Colchester Place',
            'zip' => 8005,
            'city' => 'Zürich',
            'country_code' => 'CH',
            'house_number' => 36,
        ],
        execution_date: '2024-01-08',
        iban: 'CH8100700110005554634',
    )
))->dto();
```

### Reports

```php
/**
 * Journal
 */
$journals = $connector->send(new JournalRequest())->dto();
```

### Salutations

```php
/**
 * Fetch A List Of Salutations
 */
$salutations = $connector->send(new FetchAListOfSalutationsRequest())->dto();
```

```php
/**
 * Fetch A Salutation
 */
$salutation = $connector->send(new FetchASalutationRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Salutations
 */
$salutations = $connector->send(new SearchSalutationsRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Salutation
 */
$salutation = $connector->send(new CreateSalutationRequest(
    data: new CreateEditSalutationDTO(
        name: 'Test',
        is_archived: false,
    )
));
```

```php
/**
 * Edit Salutation
 */
$salutation = $connector->send(new EditASalutationRequest(
    id: 1,
    data: new CreateEditSalutationDTO(
        name: 'Test Edit',
        is_archived: false,
    )
));
```

```php
/**
 * Delete Salutation
 */
$salutation = $connector->send(new DeleteASalutationRequest(
    id: 1
));
```

### Taxes

```php
/**
 * Fetch A List Of Taxes
 */
$taxes = $connector->send(new FetchAListOfTaxesRequest())->dto();
```

```php
/**
 * Fetch A Tax
 */
$tax = $connector->send(new FetchATaxRequest(
    id: 1
))->dto();
```

```php
/**
 * Delete A Tax
 */
$tax = $connector->send(new DeleteATaxRequest(
    id: 1
));
```

### Titles

```php
/**
 * Fetch A List Of Titles
 */
$titles = $connector->send(new FetchAListOfTitlesRequest())->dto();
```

```php
/**
 * Fetch A Title
 */
$title = $connector->send(new FetchATitleRequest(
    id: 1
))->dto();
```

```php
/**
 * Search Titles
 */
$titles = $connector->send(new SearchTitlesRequest(
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Title
 */
$title = $connector->send(new CreateTitleRequest(
    data: new CreateEditTitleDTO(
        name: 'Test',
        is_archived: false,
    )
));
```

```php
/**
 * Edit Title
 */
$title = $connector->send(new EditATitleRequest(
    id: 1,
    data: new CreateEditTitleDTO(
        name: 'Test Edit',
        is_archived: false,
    )
));
```

```php
/**
 * Delete Title
 */
$title = $connector->send(new DeleteATitleRequest(
    id: 1
));
```

### VAT Periods

```php
/**
 * Fetch A List Of VAT Periods
 */
$vatPeriods = $connector->send(new FetchAListOfVatPeriodsRequest())->dto();
```

```php
/**
 * Fetch A VAT Period
 */
$vatPeriod = $connector->send(new FetchAVatPeriodRequest(
    id: 1
))->dto();
```

### OpenID Connect

```php
/**
 * Fetch OpenID Userinfo (requires OAuth2)
 */
$userinfo = $connector->send(new FetchUserinfoRequest())->dto();
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

-   [Rhys Lees](https://github.com/RhysLees)
-   [Sebastian Fix](https://github.com/StanBarrows)
-   [All Contributors](../../contributors)
-   [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
-   [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)
-   [Laravel Saloon by Sam Carré](https://github.com/Sammyjo20/Saloon)

## 🎭 License

The MIT License (MIT). Please have a look at [License File](LICENSE.md) for more information.
