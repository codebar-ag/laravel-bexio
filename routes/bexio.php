<?php

use CodebarAg\Bexio\Http\Controllers\BexioOAuthController;
use Illuminate\Support\Facades\Route;

/**
 * Bexio OAuth routes.
 *
 * The default prefix is 'bexio'. It can be customized via 'route_prefix' in config/bexio.php.
 * If you change route names, update the connector accordingly.
 */
Route::middleware(array_merge(['web'], config('bexio.route_middleware')))->prefix(config('bexio.route_prefix') ?? 'bexio')->group(function () {
    Route::get('/redirect', [BexioOAuthController::class, 'redirect'])->name('bexio.oauth.redirect');
    Route::get('/callback', [BexioOAuthController::class, 'callback'])->name('bexio.oauth.callback');
});
