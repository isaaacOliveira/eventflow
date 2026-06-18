<?php

use Livewire\Component;
use App\Models\Evento;
use App\Models\Inscricao;
use App\Models\User;
use App\Models\Presenca;

new class extends Component {

    public function rendering($view) {
        $view->title('Painel');
    }

    public function with()
    {
        $eventosRanking = Evento::withCount('inscricoes')
            ->orderBy('inscricoes_count', 'desc')
            ->take(5)
            ->get();

        return [
            'stats' => [
                'eventos_ativos' => Evento::where('data_fim', '>=', now()->startOfDay())->count(),
                'total_inscritos' => Inscricao::count(),
                'inscricoes_canceladas' => 0, 
                //'presencas_confirmadas' => 0,
                'presencas_confirmadas' => Presenca::whereNotNull('data_checkin')->count(),
                'total_usuarios' => User::count(),
                'inscricoes_canceladas' => Inscricao::onlyTrashed()->count(),
            ],
            'eventosRanking' => $eventosRanking,
            // Preparamos os dados para o JavaScript
            'chartLabels' => $eventosRanking->pluck('titulo')->toArray(),
            'chartData' => $eventosRanking->pluck('inscricoes_count')->toArray(),
        ];
    }
}; ?>

        <div wire:poll.5s class="p-6 w-full space-y-6">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <flux:heading size="xl" level="1">Painel Estatístico</flux:heading>
            <flux:subheading>Visão geral do sistema de eventos e inscrições</flux:subheading>

            <flux:separator class="my-4" />

            {{-- Grid de Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <flux:card class="flex flex-col items-center justify-center p-6 text-center">
                    <flux:icon.calendar-days class="size-8 text-blue-500 mb-2" />
                    <flux:heading size="lg">{{ $stats['eventos_ativos'] }}</flux:heading>
                    <flux:subheading>Eventos Disponíveis</flux:subheading>
                </flux:card>

                <flux:card class="flex flex-col items-center justify-center p-6 text-center">
                    <flux:icon.users class="size-8 text-green-500 mb-2" />
                    <flux:heading size="lg">{{ $stats['total_inscritos'] }}</flux:heading>
                    <flux:subheading>Total de Inscritos</flux:subheading>
                </flux:card>

                <flux:card class="flex flex-col items-center justify-center p-6 text-center">
                    <flux:icon.check-badge class="size-8 text-indigo-500 mb-2" />
                    <flux:heading size="lg">{{ $stats['presencas_confirmadas'] }}</flux:heading>
                    <flux:subheading>Presenças Confirmadas</flux:subheading>
                </flux:card>

                <flux:card class="flex flex-col items-center justify-center p-6 text-center">
                    <flux:icon.x-circle class="size-8 text-red-500 mb-2" />
                    <flux:heading size="lg">{{ $stats['inscricoes_canceladas'] }}</flux:heading>
                    <flux:subheading>Cancelamentos</flux:subheading>
                </flux:card>
            </div>

           <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Gráfico de Barras --}}
        <flux:card class="lg:col-span-2" wire:ignore>
            <flux:heading size="md" class="mb-4">Inscrições por Evento (Visual)</flux:heading>
            <div class="h-64">
                <canvas id="rankingChart"></canvas>
            </div>
        </flux:card>

        {{-- Comunidade --}}
        <div class="space-y-6">
            <flux:card class="bg-zinc-50 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-4 text-zinc-500 uppercase">Resumo da Comunidade</flux:heading>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span>Usuários Registrados</span>
                        <b>{{ $stats['total_usuarios'] }}</b>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>

    {{-- Lógica do Gráfico --}}
    <script>
        document.addEventListener('livewire:navigated', () => {
            const ctx = document.getElementById('rankingChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @js($chartLabels),
                    datasets: [{
                        label: 'Número de Inscritos',
                        data: @js($chartData),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
        </div>
  