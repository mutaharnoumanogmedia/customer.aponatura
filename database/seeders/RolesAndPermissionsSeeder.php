<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1) Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2) Define your resources and actions
        $resources = [
            'banner'   => 'Promotional Banners',
            'product'  => 'Products',
            'order'    => 'Orders',
            'customer' => 'Customers',
            'brand'    => 'Brands',
            'role'     => 'Roles',
            'user'     => 'Users',
            'setting'  => 'Settings',
        ];

        $actions = ['create', 'read', 'update', 'delete'];

        // 3) Create CRUD permissions for each resource
        foreach ($resources as $key => $label) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name'       => "{$key}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // 4) One extra for reports
        Permission::firstOrCreate([
            'name'       => 'report.view',
            'guard_name' => 'web',
        ]);

        // 5) Create roles and assign permissions

        // a) Super-Admin → all permissions
        $admin = Role::firstOrCreate([
            'name'       => 'Admin',
            'guard_name' => 'web',
        ]);
        $admin->givePermissionTo(Permission::all());

        // b) Product Manager → manage products & brands
        $pm = Role::firstOrCreate([
            'name'       => 'Product Manager',
            'guard_name' => 'web',
        ]);
        $pm->givePermissionTo([
            'product.create',
            'product.read',
            'product.update',
            'product.delete',
            'brand.create',
            'brand.read',
            'brand.update',
            'brand.delete',
        ]);

        // c) Marketing Manager → manage banners only
        $mm = Role::firstOrCreate([
            'name'       => 'Marketing Manager',
            'guard_name' => 'web',
        ]);
        $mm->givePermissionTo([
            'banner.create',
            'banner.read',
            'banner.update',
            'banner.delete',
        ]);

        // d) Sales Manager → full orders + read/update customers
        $sm = Role::firstOrCreate([
            'name'       => 'Sales Manager',
            'guard_name' => 'web',
        ]);
        $sm->givePermissionTo([
            'order.create',
            'order.read',
            'order.update',
            'order.delete',
            'customer.read',
            'customer.update',
        ]);

        // e) Analyst → view reports only
        $an = Role::firstOrCreate([
            'name'       => 'Analyst',
            'guard_name' => 'web',
        ]);
        $an->givePermissionTo('report.view');
    }
}
