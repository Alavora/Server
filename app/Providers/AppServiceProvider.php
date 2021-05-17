<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        //
        Schema::defaultStringLength(191);

        //     DB::listen(function ($query) {
        //         $query->sql;
        //         $query->bindings;
        //         $query->time;
        //     file_put_contents('php://stdout', "[SQL] {$query->sql} \n" .
        //     "      bindings:\t".json_encode($query->bindings)."\n".
        //     "      time:\t{$query->time} milliseconds\n");
        // });
    }
}
