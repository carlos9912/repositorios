<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Menu;
use App\Models\Usuario;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 06/05/2018
 */
class AppServiceProvider extends ServiceProvider
{

    /**
     *
     * @tutorial Method Description: Bootstrap any application services.
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {06/05/2018}
     */
    public function boot()
    {
        //view()->composer('layouts.admin', function ($view)
        //{
        //    $view->with('notificaciones', Usuario::notificaciones());
        //});
        Schema::defaultStringLength(200);
    }

    /**
     *
     * @tutorial Method Description: Register any application services.
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {06/05/2018}
     */
    public function register()
    {
        //
    }
}
