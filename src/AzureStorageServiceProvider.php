<?php

namespace FutureOriented\LaravelFlysystemAzure;

use FutureOriented\FlysystemAzureAdapter\AzureAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Common\ServicesBuilder;

class AzureStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('azure', function ($app, $config) {
            $connectionString = sprintf('DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;BlobEndpoint=%s',
                $config['protocol'],
                $config['account']['name'],
                $config['account']['key'],
                $config['endpoint']['blob']
            );

            $config['container'] = 'visa-test';
            $proxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

            return new Filesystem(new AzureAdapter($proxy, $config['container']));
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}