<?php

namespace Database\Factories\Dealskoo\Coupon\Models;

use Dealskoo\Coupon\Models\Coupon;
use Dealskoo\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'slug' => $this->faker->slug,
            'ship_fee' => $this->faker->numberBetween(0, 20),
            'code' => $this->faker->slug,
            'product_id' => Product::factory()->approved(),
            'price' => function ($coupon) {
                return $this->faker->numberBetween(0, Product::find($coupon['product_id'])->price);
            },
            'seller_id' => function ($coupon) {
                return Product::find($coupon['product_id'])->seller_id;
            },
            'category_id' => function ($coupon) {
                return Product::find($coupon['product_id'])->category_id;
            },
            'country_id' => function ($coupon) {
                return Product::find($coupon['product_id'])->country_id;
            },
            'brand_id' => function ($coupon) {
                return Product::find($coupon['product_id'])->brand_id;
            },
            'platform_id' => function ($coupon) {
                return Product::find($coupon['product_id'])->platform_id;
            },
            'start_at' => $this->faker->dateTime,
            'end_at' => $this->faker->dateTime,
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'approved_at' => $this->faker->dateTime,
            ];
        });
    }

    public function avaiabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'start_at' => $this->faker->dateTimeBetween('-1 days'),
                'end_at' => $this->faker->dateTimeBetween('now', '+7 days'),
                'approved_at' => $this->faker->dateTime,
            ];
        });
    }
}
