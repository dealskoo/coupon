<?php

namespace Dealskoo\Coupon\Tests\Unit;

use Dealskoo\Coupon\Models\Coupon;
use Dealskoo\Image\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Coupon\Tests\TestCase;
use Illuminate\Support\Str;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_cover()
    {
        $image = Image::factory()->make();
        $coupon = Coupon::factory()->create();
        $coupon->product->images()->save($image);
        $this->assertNotNull($coupon->cover);
    }

    public function test_slug()
    {
        $slug = 'Product';
        $coupon = Coupon::factory()->create();
        $coupon->slug = $slug;
        $coupon->save();
        $coupon->refresh();
        $this->assertEquals($coupon->slug, Str::lower($slug));
    }

    public function test_country()
    {
        $coupon = Coupon::factory()->create();
        $this->assertNotNull($coupon->country);
    }

    public function test_category()
    {
        $coupon = Coupon::factory()->create();
        $this->assertNotNull($coupon->category);
    }

    public function test_brand()
    {
        $coupon = Coupon::factory()->create();
        $this->assertNotNull($coupon->brand);
    }

    public function test_platform()
    {
        $coupon = Coupon::factory()->create();
        $this->assertNotNull($coupon->platform);
    }

    public function test_seller()
    {
        $coupon = Coupon::factory()->create();
        $this->assertNotNull($coupon->seller);
    }

    public function test_off()
    {
        $coupon = Coupon::factory()->create();
        $this->assertLessThan($coupon->product->price, $coupon->price);
        $this->assertLessThan(100, $coupon->off);
    }

    public function test_approved()
    {
        $coupon = Coupon::factory()->approved()->create();
        $this->assertCount(Coupon::approved()->count(), Coupon::all());
    }

    public function test_avaiabled()
    {
        $coupon = Coupon::factory()->avaiabled()->create();
        $this->assertCount(Coupon::avaiabled()->count(), Coupon::all());
    }
}
