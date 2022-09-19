<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//php artisan make:provider SampleServiceProvider で作成
class SampleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //サービスを登録するコード
        app()->bind('serviceProviderTest', function(){
            return 'サービスプロバイダのテストです';
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
