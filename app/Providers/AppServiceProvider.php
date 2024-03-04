<?php

namespace App\Providers;

use App\Models\Conversation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Models\GenUser;
use Illuminate\Support\Facades\Schema;
use App\Models\Settings;

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
        View::share('conversationid', Session::get('conversationid'));
        Schema::defaultStringLength(191);
        
        $settings = array();
        foreach(['display','user','general','kiq'] as $section){
            $settings += [$section => array()];
            foreach(Settings::where('section',$section)->get() as $set){
                $settings[$section]+=[$set['name']=>$set];
            }
        }
        
        /* view()->composer('*',function($view) {
            $view->with('settings', $settings);
        }); */
        
       view()->share('settings', $settings); 
    }
}
