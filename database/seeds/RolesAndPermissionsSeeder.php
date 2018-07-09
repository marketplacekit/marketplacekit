<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'edit listing']);
        Permission::create(['name' => 'publish listing']);
        Permission::create(['name' => 'unpublish listing']);
        Permission::create(['name' => 'disable listing']);
        Permission::create(['name' => 'ban user']);

        // create roles and assign created permissions

        $role = Role::create(['name' => 'member']);

        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo(['edit listing', 'publish listing', 'unpublish listing']);

        $role = Role::create(['name' => 'moderator']);
        $role->givePermissionTo(['edit listing', 'disable listing', 'publish listing', 'unpublish listing', 'ban user']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $user = \App\Models\User::first();
        $user->assignRole('admin');
    }
}
