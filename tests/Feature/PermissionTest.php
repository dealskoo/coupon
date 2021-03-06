<?php

namespace Dealskoo\Coupon\Tests\Feature;

use Dealskoo\Admin\Facades\PermissionManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Coupon\Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_permissions()
    {
        $this->assertNotNull(PermissionManager::getPermission('coupons.index'));
        $this->assertNotNull(PermissionManager::getPermission('coupons.show'));
        $this->assertNotNull(PermissionManager::getPermission('coupons.edit'));
    }
}
