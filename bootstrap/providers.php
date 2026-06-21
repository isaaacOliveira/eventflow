<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Garante que esta linha está aqui no topo

class AppServiceProvider extends ServiceProvider
{
/**
* Register any application services.
*/
public function register(): void
{
//
}

/**
* Bootstrap any application services.
*/
public function boot(): void
{
// Se o site estiver no Render (produção), força o HTTPS
if (config('app.env') === 'production' || isset($_SERVER['HTTPS'])) {
URL::forceScheme('https');
}
}
}