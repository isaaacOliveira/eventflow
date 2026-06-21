<?php

namespace Database\Seeders;

use App\Models\User;
// // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- Importação necessária para as permissões da Spatie

class DatabaseSeeder extends Seeder
{
/**
* Seed the application's database.
*/
public function run(): void
{
// // User::factory(10)->create();

// Código padrão que já tinhas no projeto
User::factory()->create([
'name' => 'Test User',
'email' => 'test@example.com',
]);

//  CRIAÇÃO DO ADMINISTRADOR DA PLATAFORMA

// 1. Garante que a role 'admin' existe na base de dados do Aiven
$adminRole = Role::firstOrCreate(['name' => 'admin']);

// 2. Procura pelo teu utilizador real através do email que registaste
$user = User::where('email', 'pedrohokaoliveira@gmail.com')->first();

// 3. Se o teu utilizador for encontrado, o Laravel atribui-te a role de admin
if ($user) {
$user->assignRole($adminRole);
}
}
}