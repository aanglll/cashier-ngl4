<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        $productPermissions = ['create products', 'edit products', 'delete products', 'view products'];

        $productCategoryPermissions = ['create product categories', 'edit product categories', 'delete product categories', 'view product categories'];

        $productUnitPermissions = ['create product units', 'edit product units', 'delete product units', 'view product units'];

        $customerPermissions = ['create customers', 'edit customers', 'delete customers', 'view customers'];

        $userPermissions = ['create users', 'edit users', 'delete users', 'view users'];

        $rolePermissions = ['create role', 'edit role', 'delete role', 'view role'];

        foreach (array_merge($productPermissions, $productCategoryPermissions, $productUnitPermissions, $customerPermissions, $userPermissions, $rolePermissions) as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        $roleSuperAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleOfficer = Role::firstOrCreate(['name' => 'officer']);
        $roleWarehouseAdmin = Role::firstOrCreate(['name' => 'warehouse admin']);

        $roleSuperAdmin->syncPermissions(array_merge($productPermissions, $productCategoryPermissions, $productUnitPermissions, $customerPermissions, $userPermissions, $rolePermissions));

        $roleAdmin->syncPermissions(array_merge($productPermissions, $productCategoryPermissions, $productUnitPermissions, $customerPermissions, $userPermissions, $rolePermissions));

        $officerPermissions = array_diff($customerPermissions, ['delete customers']);
        $roleOfficer->syncPermissions(array_merge($officerPermissions, ['view products']));

        $productPermissionsForWarehouseAdmin = ['view products'];
        $productCategoryPermissionsForWarehouseAdmin = ['view product categories'];
        $roleWarehouseAdmin->syncPermissions(array_merge($productPermissionsForWarehouseAdmin, $productCategoryPermissionsForWarehouseAdmin));

        // $user = \App\Models\User::find(1);
        // if ($user) {
        //     $user->assignRole('superadmin');
        //     $user->update(['user_priv' => 'superadmin']);
        // }

        // $user = \App\Models\User::find(4);
        // if ($user) {
        //     $user->assignRole('admin');
        //     $user->update(['user_priv' => 'admin']);
        // }

        // $user = \App\Models\User::find(3);
        // if ($user) {
        //     $user->assignRole('officer');
        //     $user->update(['user_priv' => 'officer']);
        // }

        // $user = \App\Models\User::find(2);
        // if ($user) {
        //     $user->assignRole('warehouse admin');
        //     $user->update(['user_priv' => 'warehouse admin']);
        // }
    }
}
