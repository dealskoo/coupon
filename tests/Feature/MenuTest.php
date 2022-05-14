<?php

namespace Dealskoo\Coupon\Tests\Feature;

use Dealskoo\Admin\Facades\AdminMenu;
use Dealskoo\Seller\Facades\SellerMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Coupon\Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu()
    {
        $this->assertNotNull(AdminMenu::findBy('title', 'coupon::coupon.coupons'));
        $this->assertNotNull(SellerMenu::findBy('title', 'coupon::coupon.coupons'));
    }
}
