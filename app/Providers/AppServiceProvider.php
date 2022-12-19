<?php

namespace App\Providers;

use App\Interfaces\AreaInterface;
use App\Interfaces\OCRInterface;
use App\Interfaces\TargetInterface;
use App\Models\Graph\Area as AreaModel;
use App\Services\GoogleOCR as GoogleOCRService;
use App\Services\Graph\Target as TargetService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OCRInterface::class, GoogleOCRService::class);
        $this->app->bind(TargetInterface::class, TargetService::class);
        $this->app->bind(AreaInterface::class, AreaModel::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
