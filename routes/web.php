<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\Evento;

Route::get('/', function () {
// Busca todos os eventos mais recentes do banco de dados
$eventos = Evento::latest()->get();

return view('welcome', compact('eventos'));


})->name('home'); 



//Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    //Route::view('dashboard', 'dashboard')->name('dashboard');
    Volt::route('/dashboard', 'dashboard')->name('dashboard');


    Volt::route('users', 'users.user-index')->middleware('can:visualizar_users')->name('users.index');
    Volt::route('users/create', 'users.user-create')->middleware('can:criar_users')->name('users.create');
    Volt::route('users/{user}/edit', 'users.user-edit')->middleware('can:editar_users')->name('users.edit'); 

    /*Volt::route('posts', 'posts.post-index')->name('posts.index');
    Volt::route('posts/create', 'posts.post-create')->name('posts.create');
    Volt::route('posts/{post}/edit', 'posts.post-edit')->name('posts.edit');*/

    Volt::route('roles', 'roles.role-index')->middleware('can:visualizar_roles')->name('roles.index');
    Volt::route('roles/create', 'roles.role-create')->middleware('can:criar_roles')->name('roles.create');
    Volt::route('roles/{role}/edit', 'roles.role-edit')->middleware('can:editar_roles')->name('roles.edit');

    Volt::route('permissions', 'permissions.permission-index')->middleware('can:visualizar_permissions')->name('permissions.index');
    Volt::route('permissions/create', 'permissions.permission-create')->middleware('can:criar_permissions')->name('permissions.create');
    Volt::route('permissions/{permission}/edit', 'permissions.permission-edit')->middleware('can:editar_permissions')->name('permissions.edit');

    Volt::route('eventos', 'eventos.evento-index')->name('eventos.index');
    Volt::route('eventos/create', 'eventos.evento-create')->middleware('can:criar_eventos')->name('eventos.create');
    Volt::route('eventos/{evento}/edit', 'eventos.evento-edit')->middleware('can:editar_eventos')->name('eventos.edit');

    Volt::route('inscricaos', 'inscricaos.inscricao-index')->middleware('can:visualizar_inscricaos')->name('inscricaos.index');
    
    Volt::route('inscricaos/create', 'inscricaos.inscricao-create')->middleware('can:criar_inscricaos')->name('inscricaos.create');

    Volt::route('inscricaos/{inscricao}/edit', 'inscricaos.inscricao-edit')->middleware('can:editar_inscricaos')->name('inscricaos.edit');
    
    Volt::route('presencas', 'presencas.presenca-index')->middleware('can:visualizar_presencas')->name('presencas.index');
    Volt::route('presencas/create', 'presencas.presenca-create')->middleware('can:criar_presencas')->name('presencas.create');

    Volt::route('presencas/{presenca}/edit', 'presencas.presenca-edit')->middleware('can:editar_presencas')->name('presencas.edit');


});

require __DIR__.'/settings.php';
