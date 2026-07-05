<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\Helper;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
		require_once app_path('ownerId_helper.php');
		 
		View::composer('*', function ($view) {

			$hasProprietorship = false;
			if (Auth::check()) {
				$user = Auth::user();
				if (in_array($user->u_type, [2, 5])) {
					$hasProprietorship = Helper::hasProprietorship();
				}
			}
			$view->with('hasProprietorship', $hasProprietorship);
		});
    }
}
