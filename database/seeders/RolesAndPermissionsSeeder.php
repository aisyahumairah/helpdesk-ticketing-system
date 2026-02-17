<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Ticket permissions
            'ticket.create',
            'ticket.read',
            'ticket.update',
            'ticket.delete',
            'ticket.assign',
            'ticket.reassign',
            'ticket.resolve',
            'ticket.verify',
            'ticket.reopen',
            'ticket.escalate',
            
            // User permissions
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'user.reset_password',
            'user.toggle_status',
            
            // Role permissions
            'role.create',
            'role.read',
            'role.update',
            'role.delete',
            'role.attach_permissions',
            
            // Permission permissions
            'permission.create',
            'permission.read',
            'permission.update',
            'permission.delete',
            
            // Settings permissions
            'settings.read',
            'settings.update',
            
            // Audit Trail permissions
            'audit_trail.read',
            
            // Reports permissions
            'report.read',
            
            // Mail Template permissions
            'mail_template.create',
            'mail_template.read',
            'mail_template.update',
            'mail_template.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // IT Support role
        $itSupportRole = Role::create(['name' => 'it_support']);
        $itSupportRole->givePermissionTo([
            'ticket.read',
            'ticket.update',
            'ticket.assign',
            'ticket.reassign',
            'ticket.resolve',
            'ticket.escalate',
            'user.read',
            'audit_trail.read',
            'report.read',
        ]);

        // User role (regular users)
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'ticket.create',
            'ticket.read',
            'ticket.verify',
            'ticket.reopen',
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
