<?php

namespace Dealskoo\Coupon\Tests\Feature\Seller;

use Carbon\Carbon;
use Dealskoo\Country\Models\Country;
use Dealskoo\Coupon\Models\Coupon;
use Dealskoo\Product\Models\Product;
use Dealskoo\Seller\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Coupon\Tests\TestCase;
use Illuminate\Support\Arr;

class CouponControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.coupons.index'));
        $response->assertStatus(200);
    }

    public function test_table()
    {
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.coupons.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonPath('recordsTotal', 0);
        $response->assertStatus(200);
    }

    public function test_create()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.coupons.create'));
        $response->assertStatus(200);
    }

    public function test_store()
    {
        $seller = Seller::factory()->create();
        $product = Product::factory()->approved()->create(['seller_id' => $seller->id]);
        $coupon = Coupon::factory()->make(['seller_id' => $seller->id, 'product_id' => $product->id]);
        $response = $this->actingAs($seller, 'seller')->post(route('seller.coupons.store'), Arr::collapse([$coupon->only([
            'title',
            'code',
            'price',
            'product_id',
            'ship_fee'
        ]), ['activity_date' => Carbon::parse($coupon->start_at)->format('m/d/Y') . ' - ' . Carbon::parse($coupon->end_at)->format('m/d/Y')]]));
        $response->assertStatus(302);
    }

    public function test_edit()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $seller = Seller::factory()->create();
        $coupon = Coupon::factory()->create(['seller_id' => $seller->id, 'country_id' => $country->id]);
        $response = $this->actingAs($seller, 'seller')->get(route('seller.coupons.edit', $coupon));
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $seller = Seller::factory()->create();
        $coupon = Coupon::factory()->create(['seller_id' => $seller->id]);
        $coupon1 = Coupon::factory()->make();
        $response = $this->actingAs($seller, 'seller')->put(route('seller.coupons.update', $coupon), $coupon1->only([
            'title',
            'code',
            'price',
            'product_id',
            'ship_fee'
        ]));
        $response->assertStatus(302);
    }

    public function test_destroy()
    {
        $seller = Seller::factory()->create();
        $coupon = Coupon::factory()->create(['seller_id' => $seller->id]);
        $response = $this->actingAs($seller, 'seller')->delete(route('seller.coupons.destroy', $coupon));
        $response->assertStatus(200);
    }
}
