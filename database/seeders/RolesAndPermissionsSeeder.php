<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear permisos
        $adminPermission = [
            'view-worships',
            'create-worships',
            'activate-worships',
            'deactivate-worships',
            'edit-worships',
            'view-news',
            'create-news',
            'activate-news',
            'deactivate-news',
            'edit-news',
            'view-verses',
            'create-verses',
            'activate-verses',
            'deactivate-verses',
            'edit-verses',
            'view-schedules',
            'create-schedules',
            'activate-schedules',
            'deactivate-schedules',
            'edit-schedules',
        ];

        // Verificar y crear permisos
        foreach ($adminPermission as $permissionName) {
            $existingPermission = Permission::where('name', $permissionName)->first();

            if (!$existingPermission) {
                Permission::create(['name' => $permissionName]);
            }
        }

        // Crear permisos adicionales
        $sadminpermission = [
            'view-users',
            'create-users',
            'edit-users',
            'activate-users',
            'deactivate-users',
            'delete-users',

            'view-roles',
            'create-roles',
            'activate-roles',
            'deactivate-roles',
            'edit-roles',
            'delete-roles',

            'delete-schedules',
            'delete-worships',
            'delete-verses',
            'delete-news',
        ];
        // Verificar y crear permisos
        foreach ($sadminpermission as $permissionNm) {
            $existingPermission = Permission::where('name', $permissionNm)->first();

            if (!$existingPermission) {
                Permission::create(['name' => $permissionNm]);
            }
        }

        // Crear roles
        $sadmin = Role::create(['name' => 'Superadministrador']);
        $admin = Role::create(['name' => 'Administrador']);
        $editor = Role::create(['name' => 'Editor']);
        $guest = Role::create(['name' => 'Invitado']);

        // Asignar permisos a roles
        $sadmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo($adminPermission);

        // Asignar roles a usuarios
        $sadminUser = \App\Models\User::find(1); // Reemplaza con el ID de tu usuario superadministrador
        $sadminUser->assignRole('Superadministrador');

        $adminUser = \App\Models\User::find(2); // Reemplaza con el ID de tu usuario administrador
        $adminUser->assignRole('Administrador');
    }
}
