<?php

namespace CodebarAg\Zendesk\Tests;

use CodebarAg\Zendesk\ZendeskServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\Support\DataConfig;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'CodebarAg\\Zendesk\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Provide a config array to DataConfig
        $this->app->when(DataConfig::class)
            ->needs('$config')
            ->give([]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ZendeskServiceProvider::class,
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
            $app['config']->set('zendesk.subdomain', 'codebar-zendesk');
            $app['config']->set('zendesk.auth.email_address', 'fake-email');
            $app['config']->set('zendesk.auth.api_token', 'fake-token');
        }
    }
}
