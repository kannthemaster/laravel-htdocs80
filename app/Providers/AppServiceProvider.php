<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        \URL::forceScheme('https');

        Paginator::useBootstrap();

        View::composer('lab-sub.show', function ($view) {
        // ตรวจสอบว่ามี Visit หรือไม่
        $visit = request()->route('visit') ? Visit::find(request()->route('visit')) : null;

        // ดึงข้อมูลผู้ป่วย
        $patient = $visit ? $visit->patient : Patient::find(request('patient'));

        // ส่งข้อมูลไปยัง View
        $view->with(compact('visit', 'patient'));
    });
        
    }
}
