<?php

namespace CodebarAg\Bexio\Tests;

use CodebarAg\Bexio\BexioServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Config;
use Spatie\LaravelData\Support\DataConfig;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::preventStrayRequests();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'CodebarAg\\Bexio\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Provide a config array to DataConfig
        $this->app->when(DataConfig::class)
            ->needs('$config')
            ->give([]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            BexioServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        if (is_dir(__DIR__.'/Fixtures/Saloon/') && count(scandir(__DIR__.'/Fixtures/Saloon')) > 0) {
            $app['config']->set('bexio.subdomain', 'codebar-bexio');
            $app['config']->set('bexio.auth.email_address', 'fake-email');
            $app['config']->set('bexio.auth.api_token', 'fake-token');
        }
    }
}
