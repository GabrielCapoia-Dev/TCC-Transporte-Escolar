<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache das permissões do Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permissões que serão atribuídas à role Admin
        $adminPermissions = [
            'Acessar Painel',
            'Listar Usuários',
            'Criar Usuários',
            'Editar Usuários',
            'Excluir Usuários',
            'Listar Níveis de Acesso',
            'Criar Níveis de Acesso',
            'Editar Níveis de Acesso',
            'Excluir Níveis de Acesso',
            'Listar Permissões de Execução',
            'Criar Permissões de Execução',
            'Editar Permissões de Execução',
            'Excluir Permissões de Execução',
        ];

        $userPermissions = [
            'Acessar Painel',
        ];

        // Criação (ou recuperação) das permissões
        foreach ($adminPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Criação da rule (role) Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        
        // Criação da rule (role) Usuário
        $userRole = Role::firstOrCreate(['name' => 'Usuário']);

        // Atribui todas as permissões à role Admin
        $adminRole->syncPermissions($adminPermissions);
        
        $userRole->syncPermissions($userPermissions);

        // Criação do usuário admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
            ]
        );

        // Atribui a role Admin ao usuário
        $adminUser->assignRole($adminRole);
    }
}
