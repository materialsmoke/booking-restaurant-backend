<?php

namespace App\Providers;

use App\Services\Drink\GetListOfDrinksInterface;
use App\Services\Drink\Punkapi\GetListOfDrinksService;
use App\Services\Meal\GetARandomMealInterface;
use App\Services\Meal\Themealdb\GetARandomMealService ;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton( GetARandomMealService::class, function($app){
        //     return new GetARandomMealService;
        // });

        $this->app->bind(GetListOfDrinksInterface::class, GetListOfDrinksService::class);

        $this->app->bind(GetARandomMealInterface::class, GetARandomMealService::class);
    }
}
