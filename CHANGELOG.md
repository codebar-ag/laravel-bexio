# Changelog

All notable changes to `laravel-bexio` will be documented in this file.

## [Unreleased]

### 🚨 Breaking Changes

#### OAuth Implementation

**⚠️ MAJOR BREAKING CHANGES** - The authentication system has been completely redesigned to support OAuth alongside token authentication.

##### BexioConnector Constructor Changes

**Before (Token only):**
```php
// Old constructor - DEPRECATED
$connector = new BexioConnector($token);
$connector = new BexioConnector(); // Used config('bexio.auth.token')
```

**After (OAuth + Token support):**
```php
// New constructor - REQUIRED
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithOAuth;

// Token authentication
$connector = new BexioConnector(new ConnectWithToken($token));
$connector = new BexioConnector(new ConnectWithToken()); // Uses config

// OAuth authentication  
$connector = new BexioConnector(new ConnectWithOAuth($clientId, $clientSecret, $redirectUri, $scopes));
$connector = new BexioConnector(new ConnectWithOAuth()); // Uses config

// Auto-resolve from container (default behavior)
$connector = new BexioConnector(); // Will resolve OAuth config if available
```

##### Configuration Structure Changes

**Before:**
```php
// config/bexio.php
return [
    'auth' => [
        'token' => env('BEXIO_API_TOKEN'),
    ],
];
```

**After:**
```php
// config/bexio.php
return [
    'auth' => [
        'token' => env('BEXIO_API_TOKEN'),
        'oauth' => [
            'client_id' => env('BEXIO_OAUTH_CLIENT_ID'),
            'client_secret' => env('BEXIO_OAUTH_CLIENT_SECRET'),
            'redirect_uri' => env('BEXIO_OAUTH_REDIRECT_URI'),
            'scopes' => explode(',', env('BEXIO_OAUTH_SCOPES')),
        ],
    ],
    'cache_store' => env('BEXIO_CACHE_STORE'),
    'route_prefix' => null,
    'redirect_url' => env('BEXIO_REDIRECT_URL', ''),
];
```

##### New Environment Variables Required

Add these new environment variables for OAuth support:

```dotenv
# OAuth Authentication (NEW)
BEXIO_OAUTH_CLIENT_ID=your_client_id_here
BEXIO_OAUTH_CLIENT_SECRET=your_client_secret_here
BEXIO_OAUTH_REDIRECT_URI=https://yourapp.com/bexio/callback
BEXIO_OAUTH_SCOPES=openid,profile,email,accounting,contact_show

# Optional OAuth Configuration
BEXIO_CACHE_STORE=redis
BEXIO_REDIRECT_URL=/dashboard
```

##### Service Provider Changes

- New OAuth resolver contracts are automatically registered
- OAuth routes are automatically registered at `/bexio/redirect` and `/bexio/callback`
- Route prefix can be customized via `config('bexio.route_prefix')`

##### Migration Guide

1. **Update your BexioConnector instantiation:**
   ```php
   // OLD - This will break
   $connector = new BexioConnector($token);
   
   // NEW - Required change
   $connector = new BexioConnector(new ConnectWithToken($token));
   ```

2. **Publish and update config file:**
   ```bash
   php artisan vendor:publish --provider="CodebarAg\Bexio\BexioServiceProvider" --tag="bexio-config" --force
   ```

3. **For OAuth usage:**
   - Register your application in Bexio Developer Portal
   - Add OAuth environment variables to `.env`
   - Use `ConnectWithOAuth` for OAuth authentication
   - Use built-in routes `/bexio/redirect` and `/bexio/callback`

4. **For multi-tenant applications:**
   - Implement custom `BexioOAuthConfigResolver` interface
   - Implement custom `BexioOAuthAuthenticationStoreResolver` interface
   - Optionally implement custom `BexioOAuthAuthenticationValidateResolver` interface for validation logic
   - Bind your implementations in a service provider

### ✨ New Features

- **OAuth 2.0 Support**: Full OAuth 2.0 implementation with PKCE support
- **Multi-tenant OAuth**: Support for multiple Bexio accounts via custom resolvers
- **OAuth Authentication Validation**: Custom validation logic before storing OAuth tokens with API access and custom redirects
- **Automatic Token Refresh**: OAuth tokens are automatically refreshed when expired
- **Encrypted Token Storage**: OAuth tokens are encrypted when cached
- **Built-in OAuth Routes**: Automatic OAuth flow handling
- **Configurable Cache Stores**: Support for custom cache stores for token storage
- **Comprehensive Scopes**: Support for all Bexio API and OpenID Connect scopes

#### OAuth Authentication Validation

The new `BexioOAuthAuthenticationValidateResolver` allows you to implement custom validation logic that runs after OAuth authentication but before the token is stored. This powerful feature provides:

- **API Access**: Full `BexioConnector` instance with authenticated access to Bexio API
- **Custom Validation**: Validate user permissions, company restrictions, or any business logic
- **Custom Redirects**: Return custom redirect responses with your own error handling
- **Exception Handling**: Gracefully handle API errors during validation

**Example Use Cases:**
- Validate user email against an allowlist
- Check company permissions via Bexio API calls
- Verify required OAuth scopes are present
- Implement custom business rules for authorization

**Default Behavior**: By default, all OAuth authentications are accepted (validation returns success)

### 🔧 Configuration

- **New OAuth Configuration**: Complete OAuth configuration structure
- **Route Customization**: Customizable OAuth route prefix
- **Cache Store Configuration**: Configurable cache store for token storage
- **Redirect URL Configuration**: Configurable post-authentication redirect

### 📚 Documentation

- **Updated README**: Comprehensive OAuth and multi-tenant documentation
- **OAuth Validation Documentation**: Complete guide for custom OAuth authentication validation with examples
- **Migration Examples**: Detailed migration examples for all scenarios
- **Scope Documentation**: Complete OAuth scope enumeration and documentation

### 🔄 DTO Field Updates

The following DTOs have been updated with new fields:

#### Contact & Additional Address Fields (2025-06-04)

- **ContactDTO** and **CreateEditContactDTO**: Added `street_name`, `house_number`, and `address_addition` fields
  - The `address` field is now deprecated in favor of the new structured address fields
  - These fields provide better address handling and validation

- **AdditionalAddressDTO** and **CreateEditAdditionalAddressDTO**: Added `street_name`, `house_number`, and `address_addition` fields
  - The `address` field is now deprecated in favor of the new structured address fields

#### Bank Account Fields (2025-06-06)

- **BankAccountDTO**: Added `owner_house_number` and `owner_country_code` fields
  - These fields provide more detailed owner information for bank accounts

#### Calendar Year Fields (2025-08-04)

- **CalendarYearDTO**: Added `is_annual_reporting` field
  - This field indicates whether the calendar year is used for annual reporting

