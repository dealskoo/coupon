<?php

namespace Dealskoo\Coupon\Tests;

use Dealskoo\Coupon\Providers\CouponServiceProvider;

abstract class TestCase extends \Dealskoo\Billing\Tests\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CouponServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}
