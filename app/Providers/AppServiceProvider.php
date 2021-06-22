<?php

namespace App\Providers;

use App\Facades\AuditLogFacade;
use App\Models\Setting;
use App\Repositories\Setting\Caches\SettingCacheDecorator;
use App\Repositories\Setting\Eloquent\SettingRepository;
use App\Repositories\Setting\Interfaces\SettingInterface;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $app;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }else {
            $this->app['request']->server->set('HTTPS', true);
        }
        /*$this->app->bind('path.public',function (){
            return base_path().'/public_html';
        });*/

        AliasLoader::getInstance()->alias('AuditLog', AuditLogFacade::class);

        $this->app->bind(SettingInterface::class, function () {
            return new SettingCacheDecorator(new SettingRepository(new Setting));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
