<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Сбрасываем кэш прав (важно при обновлении)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Создаём права (permissions)
        $entities = ['roles', 'permissions', 'users'];
        $actions = ['list', 'show', 'create', 'update', 'delete'];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $entity . '.' . $action]);
            }
        }
        // Создаём роль "moderator" с базовыми правами
        $userRole = Role::create(['name' => 'moderator']);
        $userRole->givePermissionTo(['users.list']);

        // Создаём роль "admin" с расширенными правами
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // все права
    }
}