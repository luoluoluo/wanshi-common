<?php
namespace Wanshi\FileClient;

use Illuminate\Support\ServiceProvider;

class FileClientServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton('file_client', function () {
            $config = config('file_client');
            return new FileClient($config['appid'], $config['appsecret'], $config['domain']);
        });
    }
}
