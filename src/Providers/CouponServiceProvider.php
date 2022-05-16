<?php

namespace Dealskoo\Coupon\Providers;

use Dealskoo\Admin\Facades\AdminMenu;
use Dealskoo\Admin\Facades\PermissionManager;
use Dealskoo\Admin\Permission;
use Dealskoo\Seller\Facades\SellerMenu;
use Illuminate\Support\ServiceProvider;

class CouponServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/coupon')
            ], 'lang');
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/seller.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'coupon');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'coupon');

        AdminMenu::route('admin.coupons.index', 'coupon::coupon.coupons', [], ['icon' => 'uil-receipt', 'permission' => 'coupons.index'])->order(2);

        PermissionManager::add(new Permission('coupons.index', 'Coupon List'));
        PermissionManager::add(new Permission('coupons.show', 'View Coupon'), 'coupons.index');
        PermissionManager::add(new Permission('coupons.edit', 'Edit Coupon'), 'coupons.index');

        SellerMenu::route('seller.coupons.index', 'coupon::coupon.coupons', [], ['icon' => 'uil-receipt me-1'])->order(3);
    }
}
