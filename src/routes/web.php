<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('statistics', 'Aphly\LaravelStatistics\Controllers\Front\StatisticsController@add');

Route::middleware(['web'])->group(function () {

    Route::prefix('statistics_admin')->middleware(['managerAuth'])->group(function () {
        Route::middleware(['rbac'])->group(function () {
            Route::get('statistics/index', 'Aphly\LaravelStatistics\Controllers\Admin\StatisticsController@index');
            Route::get('statistics/detail', 'Aphly\LaravelStatistics\Controllers\Admin\StatisticsController@detail');
            Route::post('statistics/del', 'Aphly\LaravelStatistics\Controllers\Admin\StatisticsController@del');

            $route_arr = [
                ['site','\StatisticsSiteController']
            ];

            foreach ($route_arr as $val){
                Route::get($val[0].'/index', 'Aphly\LaravelStatistics\Controllers\Admin'.$val[1].'@index');
                Route::get($val[0].'/form', 'Aphly\LaravelStatistics\Controllers\Admin'.$val[1].'@form');
                Route::post($val[0].'/save', 'Aphly\LaravelStatistics\Controllers\Admin'.$val[1].'@save');
                Route::post($val[0].'/del', 'Aphly\LaravelStatistics\Controllers\Admin'.$val[1].'@del');
            }

        });
    });

});
