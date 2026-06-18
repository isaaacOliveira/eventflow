<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eventflow - Gestão de Eventos</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.swiper { width: 100%; padding-top: 20px; padding-bottom: 60px; }
.swiper-slide { background: transparent; width: 600px; height: auto; filter: blur(2px); transition: 0.3s; overflow: visible; }
.swiper-slide-active { filter: blur(0); transform: scale(1.03); z-index: 10; }
.whatsapp-float { position: fixed; bottom: 20px; left: 20px; z-index: 100; }
</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 py-4 px-4 md:px-8 sticky top-0 z-50">
<div class="flex items-center justify-between md:justify-start gap-4 md:gap-8 w-full max-w-7xl mx-auto">
<div class="text-blue-600 font-bold text-2xl">Eventflow</div>

<div class="flex-1 max-w-2xl relative">
<input type="text" id="input-busca" placeholder="Buscar experiências" class="w-full border border-gray-300 rounded-lg py-2 px-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
<svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
</div>

<div class="flex items-center gap-4 md:gap-6 text-sm font-medium text-gray-600">
<a href="{{ route('login') }}" class="hover:text-blue-600 whitespace-nowrap text-xs md:text-sm">Criar evento</a>
<a href="{{ route('login') }}" class="hover:text-blue-600 whitespace-nowrap text-xs md:text-sm">Meus eventos</a>

<div class="relative group">
<button class="flex items-center gap-2 border border-gray-300 p-2 rounded-full hover:shadow-md transition">
<svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
<div class="bg-gray-200 rounded-full p-1 hidden sm:block">
<svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
</div>
</button>
<div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-2 hidden group-hover:block">
<a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Entrar</a>
<a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100 font-bold text-blue-600">Cadastrar-se</a>
</div>
</div>
</div>
</div>
</nav>

{{-- SEÇÃO DO CARROSSEL DINÂMICO --}}
<section class="bg-white py-10 overflow-hidden">
<div class="swiper mySwiper">
<div class="swiper-wrapper">
@foreach ($eventos->take(4) as $index => $evento)
<div class="swiper-slide flex flex-col items-center">
<div class="w-full h-[350px] rounded-2xl overflow-hidden shadow-xl border border-gray-100">
<a href="{{ route('eventos.index') }}">
@if($evento->foto_caminho)
<img src="{{ asset('storage/' . $evento->foto_caminho) }}" alt="{{ $evento->titulo }}" class="w-full h-full object-cover">
@else
<div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
<span class="text-white text-lg tracking-wider uppercase font-bold">Eventflow</span>
</div>
@endif
</a>
</div>

<div class="mt-5 text-center px-4 w-full">
<h3 class="text-xl font-bold text-gray-800 tracking-tight uppercase">{{ $evento->titulo }}</h3>

<div class="flex items-center justify-center gap-1.5 text-gray-500 text-sm mt-2">
<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
<span>{{ $evento->local }}</span>
</div>

<div class="flex items-center justify-center gap-1.5 text-gray-500 text-sm mt-1">
<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
<span>{{ \Carbon\Carbon::parse($evento->data_evento)->translatedFormat('d \d\e M') }}</span>
</div>
</div>
</div>
@endforeach
</div>
<div class="swiper-pagination mt-8"></div>
</div>
</section>

{{-- SEÇÃO AS 18 COLEÇÕES COMPLETAS --}}
<section class="max-w-7xl mx-auto px-8 py-12">
<div class="flex justify-between items-center mb-6">
<h2 class="text-xl font-bold text-gray-800">Explore nossas coleções</h2>
<button id="btn-limpar-filtro" class="text-xs font-bold text-red-500 hover:underline hidden">Limpar Filtros</button>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">

<div data-categoria="shows" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
<span class="text-xs font-semibold">Shows</span>
</div>

<div data-categoria="teatro" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4V20M17 4V20M3 8H7M17 8H21M3 12H21M3 16H7M17 16H21M4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20Z" /></svg>
<span class="text-xs font-semibold">Teatros e Espetáculos</span>
</div>

<div data-categoria="junina" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M5.3 5.3l13.4 13.4M18.7 5.3L5.3 18.7" /></svg>
<span class="text-xs font-semibold">Aniversários</span>
</div>

<div data-categoria="copa" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22a10 10 0 100-20 10 10 0 000 20zM12 6V2M12 22v-4M2 12h4M18 12h4" /></svg>
<span class="text-xs font-semibold">Copa na Envetflow</span>
</div>

<div data-categoria="promo" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 8.5L14.5 13.5M14 8.5H14.5V9M9.5 13H10V13.5M7 7H17V17H7V7Z" /></svg>
<span class="text-xs font-semibold">Promoções</span>
</div>

<div data-categoria="esportes" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5H19V11M4 19L11 12M20 4L12.5 11.5" /></svg>
<span class="text-xs font-semibold">Esportes</span>
</div>

<div data-categoria="comedy" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z" /></svg>
<span class="text-xs font-semibold">Stand Up Comedy</span>
</div>

<div data-categoria="passeios" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
<span class="text-xs font-semibold">Excursão</span>
</div>

<div data-categoria="congressos" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
<span class="text-xs font-semibold">Congressos</span>
</div>

<div data-categoria="infantil" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
<span class="text-xs font-semibold">Infantil</span>
</div>

<div data-categoria="loja" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
<span class="text-xs font-semibold">Eventos com loja</span>
</div>

<div data-categoria="descontos" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
<span class="text-xs font-semibold">Descontos</span>
</div>

<div data-categoria="cursos" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
<span class="text-xs font-semibold">Cursos</span>
</div>

<div data-categoria="gastronomia" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
<span class="text-xs font-semibold">Gastronomia</span>
</div>

<div data-categoria="pride" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" /></svg>
<span class="text-xs font-semibold">Pride</span>
</div>

<div data-categoria="religiao" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
<span class="text-xs font-semibold">Religião</span>
</div>

<div data-categoria="online" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
<span class="text-xs font-semibold">Eventos Online</span>
</div>

<div data-categoria="play" class="botao-colecao flex flex-col items-center justify-center p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:text-blue-600 cursor-pointer transition text-center group bg-white shadow-sm">
<svg class="w-7 h-7 mb-2 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
<span class="text-xs font-semibold">Workshops</span>
</div>

</div>
</section>

{{-- SEÇÃO DINÂMICA DE EVENTOS --}}
<section class="max-w-7xl mx-auto px-8 py-8">
<h2 class="text-xl font-bold mb-6 text-gray-800 border-l-4 border-blue-600 pl-3">Próximos Eventos Disponíveis</h2>

<div id="mensagem-vazio" class="hidden bg-gray-100 rounded-xl p-8 text-center text-gray-500">
<p class="text-sm font-medium">Nenhum evento correspondente encontrado.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
@forelse ($eventos as $evento)
<div class="card-evento bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition transform hover:-translate-y-1 flex flex-col h-full"
data-titulo="{{ strtolower($evento->titulo) }}"
data-descricao="{{ strtolower($evento->descricao) }}">

<div class="relative h-44 w-full bg-gray-100 overflow-hidden">
@if($evento->foto_caminho)
<img src="{{ asset('storage/' . $evento->foto_caminho) }}" alt="{{ $evento->titulo }}" class="w-full h-full object-cover">
@else
<div class="w-full h-full bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
<span class="text-white/60 text-xs tracking-wider uppercase font-bold">Eventflow</span>
</div>
@endif

<div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-[10px] font-bold text-gray-800 shadow-sm">
{{ $evento->vagas_disponiveis }} Vagas
</div>
</div>

<div class="p-4 flex flex-col justify-between flex-1">
<div>
<p class="text-blue-600 text-xs font-bold uppercase tracking-wider">
{{ \Carbon\Carbon::parse($evento->data_evento)->translatedFormat('d \d\e M \d\e Y') }}
</p>
<h3 class="font-bold text-gray-800 mt-1 uppercase text-sm line-clamp-1" title="{{ $evento->titulo }}">
{{ $evento->titulo }}
</h3>
<p class="text-gray-400 text-xs flex items-center gap-1 mt-1">
{{ \Illuminate\Support\Str::limit($evento->local, 25) }}
</p>
<p class="text-gray-600 text-xs mt-2 line-clamp-2 leading-relaxed">
{{ $evento->descricao }}
</p>
</div>

<div class="mt-4 pt-3 border-t border-gray-100">
<a href="{{ route('eventos.index') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition">
Ver mais informações
</a>
</div>
</div>
</div>
@empty
<div class="col-span-full bg-gray-100 rounded-xl p-8 text-center text-gray-500">
<p class="text-sm font-medium">Nenhum evento agendado para os próximos dias.</p>
</div>
@endforelse
</div>
</section>

<a href="https://wa.me/244934692550" target="_blank" class="whatsapp-float bg-green-500 text-white p-3 rounded-full shadow-lg flex items-center gap-2 hover:bg-green-600 transition">
<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
<span class="font-bold text-sm">Call Center Whatsapp</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
var swiper = new Swiper(".mySwiper", {
effect: "coverflow",
grabCursor: true,
centeredSlides: true,
slidesPerView: "auto",
loop: true,
autoplay: {
delay: 3500,
disableOnInteraction: false // FAZ O CARROSSEL NÃO PARAR MESMO SE HOUVER CLIQUE OU TOQUE
},
coverflowEffect: { rotate: 0, stretch: 0, depth: 100, modifier: 1.5, slideShadows: false, },
pagination: { el: ".swiper-pagination", clickable: true }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
const inputBusca = document.getElementById("input-busca");
const botoesColecao = document.querySelectorAll(".botao-colecao");
const cardsEventos = document.querySelectorAll(".card-evento");
const mensagemVazio = document.getElementById("mensagem-vazio");
const btnLimpar = document.getElementById("btn-limpar-filtro");

let filtroColecaoAtivo = "";

function aplicarFiltros() {
const termoBusca = inputBusca.value.toLowerCase().trim();
let nenhumVisivel = true;

cardsEventos.forEach(card => {
const titulo = card.getAttribute("data-titulo").toLowerCase();
const descricao = card.getAttribute("data-descricao").toLowerCase();

const correspondeBusca = titulo.includes(termoBusca) || descricao.includes(termoBusca);
const correspondeColecao = !filtroColecaoAtivo || titulo.includes(filtroColecaoAtivo) || descricao.includes(filtroColecaoAtivo);

if (correspondeBusca && correspondeColecao) {
card.style.display = "flex";
nenhumVisivel = false;
} else {
card.style.display = "none";
}
});

if (nenhumVisivel && cardsEventos.length > 0) {
mensagemVazio.classList.remove("hidden");
} else {
mensagemVazio.classList.add("hidden");
}
}

inputBusca.addEventListener("input", aplicarFiltros);

botoesColecao.forEach(botao => {
botao.addEventListener("click", function() {
const categoria = this.getAttribute("data-categoria");

botoesColecao.forEach(b => b.classList.remove("border-blue-500", "text-blue-600", "bg-blue-50"));

if (filtroColecaoAtivo === categoria) {
filtroColecaoAtivo = "";
btnLimpar.classList.add("hidden");
} else {
filtroColecaoAtivo = categoria;
this.classList.add("border-blue-500", "text-blue-600", "bg-blue-50");
btnLimpar.classList.remove("hidden");
}
aplicarFiltros();
});
});

btnLimpar.addEventListener("click", function() {
filtroColecaoAtivo = "";
inputBusca.value = "";
botoesColecao.forEach(b => b.classList.remove("border-blue-500", "text-blue-600", "bg-blue-50"));
this.classList.add("hidden");
aplicarFiltros();
});
});
</script>

<footer class="bg-white border-t border-gray-200 pt-16 pb-8 mt-12">
<div class="max-w-7xl mx-auto px-8">
<div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
<div class="col-span-1">
<div class="text-blue-600 font-bold text-2xl mb-4">Eventflow</div>
<p class="text-gray-500 text-sm leading-relaxed">
A maior plataforma de gestão de eventos em Angola. Encontre as melhores festas, concertos e experiências culturais num só lugar.
</p>
</div>
<div>
<h4 class="font-bold text-gray-800 mb-4">Planejar Eventos</h4>
<ul class="text-gray-500 text-sm space-y-2">
<li><a href="#" class="hover:text-blue-600 transition">Meus ingressos</a></li>
<li><a href="https://wa.me/244934692550" class="hover:text-blue-600 transition">Central de Ajuda</a></li>
<li><a href="#" class="hover:text-blue-600 transition">Termos de uso</a></li>
<li><a href="#" class="hover:text-blue-600 transition">Política de privacidade</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-gray-800 mb-4">Siga-nos</h4>
<div class="flex gap-4">
<a href="https://www.facebook.com/isaa_nespera" target="blank" class="text-gray-400 hover:text-blue-600 transition">
<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
</a>
<a href="https://www.instagra.com/isaa_nespera" class="text-gray-400 hover:text-pink-600 transition">
<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
</a>
</div>
</div>
</div>
<div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400 uppercase tracking-widest">
<p>© 2026 Eventflow BG. Feito em Angola.</p>
<div class="flex gap-6">
<span>Plataforma de Gestão de Eventos</span>
<span>Luanda | Benguela | Huambo</span>
</div>
</div>
</div>
</footer>
</body>
</html>