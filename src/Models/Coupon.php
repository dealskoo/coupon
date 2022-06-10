<?php

namespace Dealskoo\Coupon\Models;

use Dealskoo\Admin\Traits\HasSlug;
use Dealskoo\Brand\Traits\HasBrand;
use Dealskoo\Category\Traits\HasCategory;
use Dealskoo\Country\Traits\HasCountry;
use Dealskoo\Favorite\Traits\Favoriteable;
use Dealskoo\Like\Traits\Likeable;
use Dealskoo\Platform\Traits\HasPlatform;
use Dealskoo\Product\Traits\HasProduct;
use Dealskoo\Seller\Traits\HasSeller;
use Dealskoo\Thumb\Traits\Thumbable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Coupon extends Model
{
    use HasFactory, SoftDeletes, HasSlug, HasCategory, HasCountry, HasSeller, HasBrand, HasPlatform, HasProduct, Likeable, Favoriteable, Thumbable, Searchable;

    protected $appends = [
        'cover', 'cover_url', 'off'
    ];

    protected $fillable = [
        'title',
        'slug',
        'price',
        'ship_fee',
        'clicks',
        'code',
        'seller_id',
        'product_id',
        'category_id',
        'country_id',
        'brand_id',
        'platform_id',
        'approved_at',
        'start_at',
        'end_at',
        'last_used_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'last_used_at' => 'datetime'
    ];

    public function getCoverAttribute()
    {
        return $this->product->cover;
    }

    public function getCoverUrlAttribute()
    {
        return $this->product->cover_url;
    }

    public function getOffAttribute()
    {
        return round((1 - ($this->price / $this->product->price)) * 100);
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->whereNotNull('approved_at');
    }

    public function scopeAvaiabled(Builder $builder)
    {
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now);
    }

    public function shouldBeSearchable()
    {
        return $this->approved_at ? true : false;
    }

    public function toSearchableArray()
    {
        return $this->only([
            'title',
            'slug',
            'price',
            'ship_fee',
            'clicks',
            'code',
            'seller_id',
            'product_id',
            'category_id',
            'country_id',
            'brand_id',
            'platform_id',
            'start_at',
            'end_at',
            'last_used_at'
        ]);
    }
}
