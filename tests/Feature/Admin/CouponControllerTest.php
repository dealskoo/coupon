<?php

namespace Dealskoo\Coupon\Tests\Feature\Admin;

use Dealskoo\Admin\Models\Admin;
use Dealskoo\Country\Models\Country;
use Dealskoo\Coupon\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Coupon\Tests\TestCase;

class CouponControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $admin = Admin::factory()->isOwner()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.coupons.index'));
        $response->assertStatus(200);
    }

    public function test_table()
    {
        $admin = Admin::factory()->isOwner()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.coupons.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonPath('recordsTotal', 0);
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $admin = Admin::factory()->isOwner()->create();
        $coupon = Coupon::factory()->create(['country_id' => $country->id]);
        $response = $this->actingAs($admin, 'admin')->get(route('admin.coupons.show', $coupon));
        $response->assertStatus(200);
    }

    public function test_edit()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $admin = Admin::factory()->isOwner()->create();
        $coupon = Coupon::factory()->create(['country_id' => $country->id]);
        $response = $this->actingAs($admin, 'admin')->get(route('admin.coupons.edit', $coupon));
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $admin = Admin::factory()->isOwner()->create();
        $coupon = Coupon::factory()->create();
        $response = $this->actingAs($admin, 'admin')->put(route('admin.coupons.update', $coupon), [
            'slug' => 'deals',
            'approved' => true
        ]);
        $response->assertStatus(302);
    }
}
