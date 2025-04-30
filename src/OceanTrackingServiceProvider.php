<?php
namespace Tracking\Ocean;

use Illuminate\Support\ServiceProvider;
use Tracking\Ocean\Request\GetTrackingRequest;
use Tracking\Ocean\Request\SubscribeRequest;

class OceanTrackingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 加载迁移
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // 加载路由
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // 发布迁移文件到主应用
        $this->publishes([
            __DIR__.'/../config/ocean-tracking.php' => config_path('ocean-tracking.php'),
        ], 'ocean-tracking-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ocean-tracking.php', 'ocean-tracking'
        );
        $this->app->singleton(SubscribeRequest::class, fn() => new SubscribeRequest());
        $this->app->singleton(GetTrackingRequest::class, fn() => new GetTrackingRequest());
    }
}