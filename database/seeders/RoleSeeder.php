<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //-----------------------------------------
        //Roles necesarios para la página.
        //-----------------------------------------
        $admin = Role::create(['name' => 'Admin']);
        $agent = Role::create(['name' => 'Agent']);
        $subAgent = Role::create(['name' => 'Sub_Agent']);

        //-----------------------------------------
        //Permisos para los roles
        //-----------------------------------------

        //Admin
        Permission::create(['name' => 'User_managment'])->assignRole($admin);
        Permission::create(['name' => 'Sales_managment'])->assignRole($admin);
        Permission::create(['name' => 'Prizes_managment'])->assignRole($admin);
        Permission::create(['name' => 'Product_managment'])->assignRole($admin);
        Permission::create(['name' => 'Company_managment'])->assignRole($admin);

        //Agente
        Permission::create(['name' => 'General_dashboard'])->syncRoles([$agent, $admin]);
        Permission::create(['name' => 'User_adding'])->syncRoles([$agent, $admin]);
        Permission::create(['name' => 'General_sales'])->syncRoles([$agent, $admin]);
        Permission::create(['name' => 'Goal_reached by'])->syncRoles([$agent, $admin]);
        Permission::create(['name' => 'Sales_adding'])->syncRoles([$agent, $admin]);

        //Sub agente
        Permission::create(['name' => 'Individual_dashboard'])->syncRoles([$agent, $admin, $subAgent]);
        Permission::create(['name' => 'Individual_add'])->syncRoles([$agent, $admin, $subAgent]);
        Permission::create(['name' => 'Individual_sales'])->syncRoles([$agent, $admin, $subAgent]);
        Permission::create(['name' => 'Prizes_list'])->syncRoles([$agent, $admin, $subAgent]);
        Permission::create(['name' => 'Sales_graphic'])->syncRoles([$agent, $admin, $subAgent]);
        
    }
}
