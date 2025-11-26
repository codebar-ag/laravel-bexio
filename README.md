<img src="https://banners.beyondco.de/Laravel%20Bexio.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-bexio&pattern=circuitBoard&style=style_2&description=A+Laravel+Bexio+integration.&md=1&showWatermark=0&fontSize=150px&images=home&widths=500&heights=500">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-bexio.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-bexio)
[![GitHub-Tests](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/run-tests.yml)
[![GitHub Code Style](https://github.com/codebar-ag/laravel-bexio/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/fix-php-code-style-issues.yml)
[![PHPStan](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/phpstan.yml)
[![Dependency Review](https://github.com/codebar-ag/laravel-bexio/actions/workflows/dependency-review.yml/badge.svg)](https://github.com/codebar-ag/laravel-bexio/actions/workflows/dependency-review.yml)

This package was developed to give you a quick start to the Bexio API.

## 📑 Table of Contents

- [What is Bexio?](#-what-is-bexio)
- [Requirements](#-requirements)
- [Authentication](#authentication)
- [Installation](#️-installation)
- [Authentication Setup](#-authentication-setup)
  - [Environment Variables](#environment-variables)
  - [Single Tenant Authentication](#single-tenant-authentication)
    - [Token Authentication](#token-authentication)
    - [OAuth Authentication](#oauth-authentication)
    - [OAuth Flow](#oauth-flow)
    - [OAuth Callback Response](#oauth-callback-response)
  - [Multi-Tenant Authentication](#multi-tenant-authentication)
    - [Token Authentication (Multi-Tenant)](#token-authentication-multi-tenant)
    - [OAuth Authentication (Multi-Tenant)](#oauth-authentication-multi-tenant)
  - [Custom OAuth Authentication Validation (Optional)](#custom-oauth-authentication-validation-optional)
  - [Available OAuth Scopes](#available-oauth-scopes)
- [Advanced Configuration](#️-advanced-configuration)
  - [Custom Cache Store](#custom-cache-store)
  - [Custom Route Configuration](#custom-route-configuration)
- [Basic Usage](#basic-usage)
  - [Responses](#responses)
  - [Enums](#enums)
  - [DTOs](#dtos)
  - [Examples](#examples)
- [API Reference](#api-reference)
  - [Accounts](#accounts)
  - [Bank Accounts](#bank-accounts)
  - [Business Years](#business-years)
  - [Calendar Years](#calendar-years)
  - [Company Profiles](#company-profiles)
  - [Additional Addresses](#additional-addresses)
  - [Contact Groups](#contact-groups)
  - [Contact Relations](#contact-relations)
  - [Contacts](#contacts)
  - [Contact Sectors](#contact-sectors)
  - [Countries](#countries)
  - [Currencies](#currencies)
  - [Files](#files)
  - [Iban Payments](#iban-payments)
  - [Invoices](#invoices)
  - [Items](#items)
  - [Languages](#languages)
  - [Manual Entries](#manual-entries)
  - [Notes](#notes)
  - [Payments](#payments)
  - [Qr Payments](#qr-payments)
  - [Reports](#reports)
  - [Salutations](#salutations)
  - [Taxes](#taxes)
  - [Titles](#titles)
  - [VAT Periods](#vat-periods)
- [Testing](#-testing)
- [Changelog](#-changelog)
- [Contributing](#️-contributing)
  - [Code Style](#code-style)
- [Security Vulnerabilities](#️-security-vulnerabilities)
- [Credits](#-credits)
- [License](#-license)

## 💡 What is Bexio?

Bexio is a cloud-based simple business software for the self-employed, small businesses and startups.

## 🛠 Requirements

| Package 	 | PHP 	       | Laravel 	 |
|-----------|-------------|-----------|
| v13.0.0 (alpha)   | ^8.3 - ^8.5 | ^12.x      |
| v12.0.0   | ^8.2 - ^8.4 | 12.x      |
| v11.0.0   | ^8.2 - ^8.3 | 11.x      |
| v1.0.0    | ^8.2        | 10.x      |

## Authentication

The package supports multiple authentication methods for both single-tenant and multi-tenant applications:

| Method 	  | Supported 	 |
|-----------|:-----------:|
| API token |      ✅      |
| OAuth     |      ✅      |

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-bexio
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --provider="CodebarAg\Bexio\BexioServiceProvider" --tag="bexio-config"
```

## 🔐 Authentication Setup

### Environment Variables

Add the following environment variables to your `.env` file:

```dotenv
# For Token Authentication
BEXIO_API_TOKEN=your_api_token_here

# For OAuth Authentication
BEXIO_OAUTH_CLIENT_ID=your_client_id_here
BEXIO_OAUTH_CLIENT_SECRET=your_client_secret_here
BEXIO_OAUTH_SCOPES=accounting,contact_show

# Optional: Custom cache store for OAuth tokens
BEXIO_CACHE_STORE=redis

# Optional: Redirect URL after successful OAuth authentication (will redirect to / by default)
BEXIO_REDIRECT_URL=/dashboard
```

You can retrieve your API token or OAuth credentials from your [Bexio Developer Dashboard](https://delveloper.bexio.com).

For OAuth credentials, you'll need to register your application in the Bexio Developer Portal.

## 🚀 Usage

### Single Tenant Authentication

For applications that only need to authenticate with one Bexio account:

#### Token Authentication

```php
use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;

// Using environment configuration
$connector = new BexioConnector(new ConnectWithToken());

// Or with explicit token
$connector = new BexioConnector(new ConnectWithToken(token: 'your-specific-token'));
```

#### OAuth Authentication

```php
use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

// Using environment configuration
$connector = new BexioConnector(new ConnectWithOAuth());

// Or with explicit configuration
$connector = new BexioConnector(new ConnectWithOAuth(
    client_id: 'your_client_id',
    client_secret: 'your_client_secret',
    redirect_uri: 'https://yourapp.com/bexio/callback',
    scopes: ['openid', 'profile', 'email', 'accounting']
));
```

#### OAuth Flow

The package provides built-in routes for OAuth authentication:

1. **Redirect to Bexio**: `/bexio/redirect`
2. **OAuth Callback**: `/bexio/callback`

You can customize the route prefix and middleware in your config file:

```php
// config/bexio.php
'route_prefix' => 'custom-bexio-prefix',

// Add custom middleware to OAuth routes (in addition to 'web' middleware)
'route_middleware' => ['auth', 'verified'],
```

#### OAuth Callback Response

After the OAuth callback is processed, the user will be redirected to the URL specified in your configuration (`config('bexio.redirect_url')` or `/` by default) with flash session data indicating the result:

**Success Response:**
```php
// When OAuth authentication is successful
session()->get('bexio_oauth_success'); // true
session()->get('bexio_oauth_message'); // 'Successfully authenticated with Bexio.'
```

**Error Responses:**
```php
// When user rejects authorization or OAuth returns an error
session()->get('bexio_oauth_success'); // false
session()->get('bexio_oauth_message'); // 'OAuth authorization failed: access_denied'

// When required parameters (code or state) are missing
session()->get('bexio_oauth_success'); // false
session()->get('bexio_oauth_message'); // 'Missing required parameters: code or state.'
```

**Handling the callback in your controller:**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (session()->has('bexio_oauth_success')) {
            $success = session()->get('bexio_oauth_success');
            $message = session()->get('bexio_oauth_message');
            
            if ($success) {
                // OAuth authentication was successful
                // You can now use the BexioConnector to make API calls
                return view('dashboard.index')->with('success', $message);
            } else {
                // OAuth authentication failed
                return view('dashboard.index')->with('error', $message);
            }
        }

        return view('dashboard.index');
    }
}
```

### Multi-Tenant Authentication

For applications that need to authenticate with multiple Bexio accounts:

#### Token Authentication (Multi-Tenant)

```php
use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;

// Different token for each tenant
$tenantToken = $currentUser->bexio_token; // Retrieved from your database
$connector = new BexioConnector(new ConnectWithToken(token: $tenantToken));
```

#### OAuth Authentication (Multi-Tenant)

For multi-tenant OAuth, you need to create custom resolvers and bind them to Laravel's service container.

##### Step 1: Create Custom Config Resolver
> this example is with the auth user as a tenant, but you can easily modify it to another model of your choice.

```php
<?php

namespace App\Support\Bexio;

use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver as BexioOAuthConfigResolverContract;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;
use Illuminate\Support\Facades\Auth;

class BexioOAuthConfigResolver implements BexioOAuthConfigResolverContract
{
    public function resolve(): ConnectWithOAuth
    {
        $user = Auth::user();
        
        return new ConnectWithOAuth(
            client_id: $user->bexio_client_id,
            client_secret: $user->bexio_client_secret,
            scopes: $user->bexio_scopes ?? []
        );
    }
}
```

##### Step 2: Create Custom Authentication Store Resolver
> this example is with the auth user as a tenant, but you can easily modify it to another model of your choice.
> you are also not required to use cache, you could store the authenticator on the model's database entry by updating a column on the model rather than using the Cache facade.

```php
<?php

namespace App\Support\Bexio;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver as BexioOAuthAuthenticationStoreResolverContract;
use CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Saloon\Http\Auth\AccessTokenAuthenticator;

class BexioOAuthAuthenticationStoreResolver implements BexioOAuthAuthenticationStoreResolverContract
{
    protected string $cacheKey = 'bexio_oauth_authenticator';

    public function get(): ?AccessTokenAuthenticator
    {
        $userId = Auth::id();
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));
        $cacheKey = $this->cacheKey . ':' . $userId;

        if (! $cacheStore->has($cacheKey)) {
            return null;
        }

        try {
            $serialized = Crypt::decrypt($cacheStore->get($cacheKey));
            $authenticator = AccessTokenAuthenticator::unserialize($serialized);

            if ($authenticator->hasExpired()) {
                // Refresh the access token
                $resolver = App::make(BexioOAuthConfigResolver::class);
                $connector = new BexioConnector($resolver->resolve());
                
                $authenticator = $connector->refreshAccessToken($authenticator);
                $this->put($authenticator);
            }

            return $authenticator;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function put(AccessTokenAuthenticator $authenticator): void
    {
        $userId = Auth::id();
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));
        $cacheKey = $this->cacheKey . ':' . $userId;

        $serialized = $authenticator->serialize();
        $encrypted = Crypt::encrypt($serialized);

        $cacheStore->put($cacheKey, $encrypted);
    }

    public function forget(): void
    {
        $userId = Auth::id();
        $cacheStore = Cache::store(config('bexio.cache_store', config('cache.default')));
        $cacheKey = $this->cacheKey . ':' . $userId;

        $cacheStore->forget($cacheKey);
    }
}
```

##### Step 3: Register Custom Resolvers

In your `AppServiceProvider` or a dedicated service provider:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BexioServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver::class,
            \App\Support\Bexio\BexioOAuthConfigResolver::class
        );

        $this->app->bind(
            \CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationStoreResolver::class,
            \App\Support\Bexio\BexioOAuthAuthenticationStoreResolver::class
        );
    }
}
```

##### Step 4: Using Multi-Tenant OAuth

```php
use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

// The connector will:
// 1. Use your custom config resolver to get user-specific OAuth config
// 2. Use your custom auth store resolver to manage tokens per user
// 3. Automatically handle token refresh when needed
$connector = new BexioConnector();

// If you prefer, you can still provide the config yourself
$connector = new BexioConnector(new \CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth(
    client_id: Auth::user()->bexio_client_id,
    client_secret: Auth::user()->bexio_client_secret,
));

// Or you can manually use your custom resolver
$configuration = App::make(\CodebarAg\Bexio\Contracts\BexioOAuthConfigResolver::class)->resolve();

$connector = new BexioConnector($configuration);
```

### Custom OAuth Authentication Validation (Optional)

You can implement custom validation logic that runs before the OAuth authenticator is stored. This is useful for:
- Validating user permissions with API calls
- Checking company/organization restrictions  
- Implementing custom business logic
- Adding custom error handling with redirects

This feature is not limited to multi-tenant setups; it can be used in single-tenant applications as well.

**Create Custom Validation Resolver:**

```php
<?php

namespace App\Support\Bexio;

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationValidateResolver as BexioOAuthAuthenticationValidateResolverContract;
use CodebarAg\Bexio\Dto\OAuthConfiguration\BexioOAuthAuthenticationValidationResult;
use CodebarAg\Bexio\Requests\OpenID\FetchUserInfoRequest;
use CodebarAg\Bexio\Requests\CompanyProfiles\FetchACompanyProfileRequest;
use Illuminate\Support\Facades\Redirect;

class BexioOAuthAuthenticationValidateResolver implements BexioOAuthAuthenticationValidateResolverContract
{
    public function resolve(BexioConnector $connector): BexioOAuthAuthenticationValidationResult
    {
        try {
            // Example 1: Validate user info
            $userInfo = $connector->send(new FetchUserInfoRequest());
            $userData = $userInfo->json();
            
            if (!$this->isValidUser($userData)) {
                return BexioOAuthAuthenticationValidationResult::failed(
                    Redirect::to('/unauthorized')
                        ->with('error', 'User not authorized for this application')
                        ->with('user_email', $userData['email'])
                );
            }
            
            // Example 2: Validate company permissions
            $companyProfile = $connector->send(new FetchACompanyProfileRequest());
            $companyData = $companyProfile->json();
            
            if (!$this->isAllowedCompany($companyData['id'])) {
                return BexioOAuthAuthenticationValidationResult::failed(
                    Redirect::to('/company-not-allowed')
                        ->with('error', 'Your company is not authorized to use this application')
                        ->with('company_name', $companyData['name'])
                );
            }
            
            // All validations passed
            return BexioOAuthAuthenticationValidationResult::success();
            
        } catch (\Exception $e) {
            // Handle API errors during validation
            return BexioOAuthAuthenticationValidationResult::failed(
                Redirect::to('/validation-error')
                    ->with('error', 'Unable to validate OAuth permissions: ' . $e->getMessage())
            );
        }
    }
    
    private function isValidUser(array $userData): bool
    {
        // Your custom user validation logic
        return in_array($userData['email'], [
            'allowed@example.com',
            'admin@mycompany.com'
        ]);
    }
    
    private function isAllowedCompany(int $companyId): bool
    {
        // Your custom company validation logic
        $allowedCompanies = [12345, 67890];
        return in_array($companyId, $allowedCompanies);
    }
}
```

**Register the Custom Validator:**

Add to your service provider:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BexioServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ... existing bindings ...
        
        $this->app->bind(
            \CodebarAg\Bexio\Contracts\BexioOAuthAuthenticationValidateResolver::class,
            \App\Support\Bexio\BexioOAuthAuthenticationValidateResolver::class
        );
    }
}
```

**Validation Result Types:**

```php
use CodebarAg\Bexio\Dto\OAuthConfiguration\BexioOAuthAuthenticationValidationResult;
use Illuminate\Support\Facades\Redirect;

// Success - proceed with storing the authenticator
BexioOAuthAuthenticationValidationResult::success();

// Failed - use default error redirect (configured redirect_url with error message)
BexioOAuthAuthenticationValidationResult::failed();

// Failed - use custom redirect with your own error handling
BexioOAuthAuthenticationValidationResult::failed(
    Redirect::to('/custom-error-page')
        ->with('error', 'Custom validation message')
        ->with('additional_data', 'any extra data you need')
);
```

**Error Handling:**

When validation fails, users will be redirected with these session variables:

```php
// For default failed validation
session()->get('bexio_oauth_success'); // false
session()->get('bexio_oauth_message'); // 'Authentication validation failed.'

// For custom redirects, you control the session data
session()->get('error'); // Your custom error message
session()->get('user_email'); // Any additional data you passed
```

### Available OAuth Scopes

The package automatically applies default OAuth scopes for OpenID Connect authentication. These default scopes are:

- `openid` - Required for OpenID Connect authentication
- `profile` - Access to basic profile information
- `email` - Access to email address
- `company_profile` - Access to company profile information
- `offline_access` - Enables refresh token functionality for long-term access

These default scopes are automatically included in all OAuth requests and cannot be removed. You can add additional scopes as needed for your application.

The package provides enums for OAuth scopes:

```php
use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthOpenIDConnectScope;
use CodebarAg\Bexio\Enums\OAuthConfiguration\OAuthApiScope;

// OpenID Connect scopes
OAuthOpenIDConnectScope::OPENID->value;          // 'openid'
OAuthOpenIDConnectScope::PROFILE->value;         // 'profile'
OAuthOpenIDConnectScope::EMAIL->value;           // 'email'
OAuthOpenIDConnectScope::COMPANY_PROFILE->value; // 'company_profile'
OAuthOpenIDConnectScope::OFFLINE_ACCESS->value;  // 'offline_access'

// API scopes
OAuthApiScope::ACCOUNTING->value;     // 'accounting'
OAuthApiScope::CONTACT_SHOW->value;   // 'contact_show'
OAuthApiScope::CONTACT_EDIT->value;   // 'contact_edit'
// ... and many more
```

## 🔧 Advanced Configuration

### Custom Cache Store

You can specify a custom cache store for OAuth token storage:

```php
// config/bexio.php
'cache_store' => 'redis', // or any other configured cache store
```

### Custom Route Configuration

```php
// config/bexio.php
'route_prefix' => 'api/bexio',        // Custom route prefix
'redirect_url' => '/dashboard',       // Where to redirect after OAuth Callback

// Add custom middleware to OAuth routes (in addition to 'web' middleware)
'route_middleware' => ['auth', 'verified'],
```

#### Route Middleware

The OAuth routes (`/bexio/redirect` and `/bexio/callback`) automatically include the `web` middleware group by default. You can add additional middleware using the `route_middleware` configuration:

**Examples:**

```php
// Require authentication for OAuth routes
'route_middleware' => ['auth'],

// Require authentication and email verification
'route_middleware' => ['auth', 'verified'],

// Add custom middleware
'route_middleware' => ['auth', 'custom-middleware'],

// Multiple middleware with parameters
'route_middleware' => ['auth:api', 'throttle:60,1'],
```

**Common Use Cases:**

- **Authentication Required**: Use `['auth']` to ensure only authenticated users can initiate OAuth flow
- **Email Verification**: Use `['auth', 'verified']` for applications requiring email verification
- **Rate Limiting**: Use `['throttle:10,1']` to limit OAuth attempts
- **Custom Authorization**: Add your own middleware to control who can access OAuth routes

The middleware will be applied to both OAuth routes:
- `GET /bexio/redirect` - Initiates OAuth flow
- `GET /bexio/callback` - Handles OAuth callback from Bexio

## Basic Usage

After setting up authentication, create a connector instance:

```php
use CodebarAg\Bexio\BexioConnector;

// For single tenant (uses default resolvers)
$connector = new BexioConnector();

// For specific authentication
$connector = new BexioConnector(new ConnectWithToken(token: 'your-token'));
$connector = new BexioConnector(new ConnectWithOAuth(/* config */));
```

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

| Enum 	                                 | Values 	                                                                                                                                                                                                                                                        |
|----------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Accounts: SearchFieldEnum              | ACCOUNT_NO(), self FIBU_ACCOUNT_GROUP_ID(), NAME(), ACCOUNT_TYPE()                                                                                                                                                                                              |
| Accounts: AccountTypeEnum              | EARNINGS(), EXPENDITURES(), ACTIVE_ACCOUNTS(), PASSIVE_ACCOUNTS(), COMPLETE_ACCOUNTS()                                                                                                                                                                          |
| AdditionalAddresses: AddSearchTypeEnum | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| CalendarYears: VatAccountingMethodEnum | EFFECTIVE(), NET_TAX()                                                                                                                                                                                                                                          |
| CalendarYears: VatAccountingTypeEnum   | AGREED(), COLLECTED()                                                                                                                                                                                                                                           |
| ContactGroups: OrderByEnum             | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| ContactRelations: OrderByEnum          | ID(), ID_ASC(), ID_DESC(), CONTACT_ID(), CONTACT_ID_ASC(), CONTACT_ID_DESC(), CONTACT_SUB_ID(), CONTACT_SUB_ID_ASC(), CONTACT_SUB_ID_DESC(), UPDATED_AT(), UPDATED_AT_ASC(), UPDATED_AT_DESC()                                                                  |
| Contacts: OrderByEnum                  | ID(), ID_ASC(), ID_DESC(), NR(), NR_ASC(), NR_DESC(), NAME_1(), NAME_1_ASC(), NAME_1_DESC(), UPDATED_AT(), UPDATED_AT_ASC(), UPDATED_AT_DESC()                                                                                                                  |
| ContactSectors: OrderByEnum            | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC()                                                                                                                                                                                                      |
| Countries: CountriesOrderByEnum        | ID(), ID_ASC(), ID_DESC(), NAME(), NAME_ASC(), NAME_DESC(), NAME_SHORT(), NAME_SHORT_ASC(), NAME_SHORT_DESC()                                                                                                                                                    |
| IbanPayments: AllowanceTypeEnum        | FEE_PAID_BY_SENDER(), FEE_PAID_BY_RECIPIENT(), FEE_SPLIT(), NO_FEE()                                                                                                                                                                                            |
| IbanPayments: StatusEnum               | OPEN(), TRANSFERRED(), DOWNLOADED(), ERROR(), CANCELLED()                                                                                                                                                                                                       |
| Items: ItemsOrderByEnum                 | ID(), ID_ASC(), ID_DESC(), INTERN_NAME(), INTERN_NAME_ASC(), INTERN_NAME_DESC()                                                                                                                                                                                 |
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

| DTO 	                                 |
|---------------------------------------|
| AccountGroupDTO                       |
| AccountDTO                            |
| BankAccountDTO                        |
| AdditionalAddressDTO                  |
| BusinessActivityDTO                   |
| BusinessYearDTO                       |
| CalendarYearDTO                       |
| CompanyProfileDTO                     |
| ContactGroupDTO                       |
| ContactRelationDTO                    |
| ContactDTO                            |
| CreateEditContactDTO                  |
| ContactSectorDTO                      |
| CurrencyDTO                           |
| CreateCurrencyDTO                     |
| EditCurrencyDTO                       |
| ExchangeCurrencyDTO                   |
| DocumentSettingDTO                    |
| FileDTO                               |
| EditFileDTO                           |
| FileUsageDTO                          |
| InvoiceDTO                            |
| InvoicePositionDTO                    |
| InvoiceTaxDTO                         |
| ItemDTO                               |
| CreateEditItemDTO                     |
| PdfDTO                                |
| LanguageDTO                           |
| AddFileDTO                            |
| EntryDTO                              |
| ManualEntryDTO                        |
| NoteDTO                               |
| PaymentDTO                            |
| PaymentTypeDTO                        |
| ProjectDTO                            |
| JournalDTO                            |
| SalutationDTO                         |
| TaxDTO                                |
| TitleDTO                              |
| UnitDTO                               |
| UserDTO                               |
| UserInfoDTO                           |
| VatPeriodDTO                          |

In addition to the above, we also provide DTOs to be used for create and edit request for the following:

| DTO 	                                 |
|---------------------------------------|
| CreateCalendarYearDTO                 |
| CreateEditAdditionalAddressDTO        |
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

`Note: This is the preferred method of interfacing with Requests and Responses however you can still use the json, object and collect methods. and pass arrays to the requests.`

> **📝 Recent DTO Field Updates:** For information about recent DTO field additions and changes, please see the [CHANGELOG.md](CHANGELOG.md#-dto-field-updates).

### Examples

Here are some examples of how to use the package with different authentication methods:

```php
use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

// Token authentication using environment configuration
$connector = new BexioConnector(new ConnectWithToken());

// Token authentication with explicit token
$connector = new BexioConnector(new ConnectWithToken(token: 'your-specific-token'));

// OAuth authentication using environment configuration
$connector = new BexioConnector(new ConnectWithOAuth());

// OAuth authentication with explicit configuration
$connector = new BexioConnector(new ConnectWithOAuth(
    client_id: 'your_client_id',
    client_secret: 'your_client_secret',
    redirect_uri: 'https://yourapp.com/bexio/callback',
    scopes: ['openid', 'profile', 'email', 'accounting']
));

// Multi-tenant with custom resolvers (using default connector)
$connector = new BexioConnector();
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
 * Fetch A List Of Additional Addresses
 */
$additionalAddresses = $connector->send(new FetchAListOfAdditionalAddressesRequest(
    contactId: 1
))->dto();
```

```php
/**
 * Fetch An Additional Address
 */
$additionalAddress = $connector->send(new FetchAnAdditionalAddressRequest(
    contactId: 1,
    id: 1
))->dto();
```

```php
/**
 * Search Additional Addresses
 */
$additionalAddresses = $connector->send(new SearchAdditionalAddressesRequest(
    contactId: 1,
    searchField: 'Name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Additional Address
 */
$additionalAddress = $connector->send(new CreateAnAdditionalAddressRequest(
    contactId: 1,
    data: new CreateEditAdditionalAddressDTO(
        name: 'Test',
        subject: 'Test Subject',
        description: 'This is a test',
        street_name: 'Main Street',
        house_number: '123',
        address_addition: 'Apt 4B',
        postcode: 1234,
        city: 'Test City',
    )
));
```

```php
/**
 * Edit Additional Address
 */
$additionalAddress = $connector->send(new EditAnAdditionalAddressRequest(
    contactId: 1,
    id: 9,
    data: new CreateEditAdditionalAddressDTO(
        name: 'Test Edit',
        subject: 'Test Subject Edit',
        description: 'This is a test edit',
        street_name: 'Main Street',
        house_number: '456',
        address_addition: 'Suite 2',
        postcode: 4567,
        city: 'Test City Edit',
    )
));
```

```php
/**
 * Delete Additional Address
 */
$additionalAddress = $connector->send(new DeleteAnAdditionalAddressRequest(
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
        name_1: 'Name',
        street_name: 'Main Street',
        house_number: '123',
        address_addition: 'Apt 4B',
        postcode: '1234',
        city: 'Zurich'
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
        name_1: 'Name',
        street_name: 'Main Street',
        house_number: '123',
        address_addition: 'Apt 4B',
        postcode: '1234',
        city: 'Zurich'
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

### Items
```php
/**
 * Fetch A List Of Items
 */
$items = $connector->send(new FetchAListOfItemsRequest())->dto();
```

```php
/**
 * Fetch An Item
 */
$item = $connector->send(new FetchAnItemRequest(
    article_id: 1
))->dto();
```

```php
/**
 * Search Items
 */
$items = $connector->send(new SearchItemsRequest(
    searchField: 'intern_name',
    searchTerm: 'Something'
))->dto();
```

```php
/**
 * Create Item
 */
$item = $connector->send(new CreateItemRequest(
    data: new CreateEditItemDTO(
        user_id: 1,
        article_type_id: 1,
        intern_code: 'ITEM-001',
        intern_name: 'Test Item',
        intern_description: 'Item Description',
        sale_price: '20.00',
        purchase_price: '10.00',
        currency_id: 1,
        tax_income_id: 14,
        tax_expense_id: 21,
        unit_id: 1,
    )
))->dto();
```

```php
/**
 * Edit Item
 */
$item = $connector->send(new EditAnItemRequest(
    article_id: 1,
    data: new CreateEditItemDTO(
        user_id: 1,
        article_type_id: 1,
        intern_code: 'ITEM-001',
        intern_name: 'Updated Item Name',
        intern_description: 'Updated Description',
        sale_price: '25.00',
        purchase_price: '15.00',
    )
))->dto();
```

```php
/**
 * Delete Item
 */
$response = $connector->send(new DeleteAnItemRequest(
    article_id: 1
));
```

### Countries
```php
/**
 * Fetch A List Of Countries
 */
$countries = $connector->send(new FetchAListOfCountriesRequest())->dto();
```

```php
/**
 * Fetch A Country
 */
$country = $connector->send(new FetchACountryRequest(
    country_id: 1
))->dto();
```

```php
/**
 * Search Countries
 */
$countries = $connector->send(new SearchCountriesRequest(
    searchField: 'name',
    searchTerm: 'Switzerland'
))->dto();
```

```php
/**
 * Create Country
 */
$country = $connector->send(new CreateCountryRequest(
    data: new CreateEditCountryDTO(
        name: 'Switzerland',
        name_short: 'CH',
        iso3166_alpha2: 'CH'
    )
))->dto();
```

```php
/**
 * Edit Country
 */
$country = $connector->send(new EditACountryRequest(
    country_id: 1,
    data: new CreateEditCountryDTO(
        name: 'Switzerland',
        name_short: 'CH',
        iso3166_alpha2: 'CH'
    )
))->dto();
```

```php
/**
 * Delete Country
 */
$response = $connector->send(new DeleteACountryRequest(
    country_id: 1
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
- [Kasper Nowak](https://github.com/kaspernowak)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)
- [Laravel Saloon by Sam Carré](https://github.com/Sammyjo20/Saloon)

## 🎭 License

The MIT License (MIT). Please have a look at [License File](LICENSE.md) for more information.
