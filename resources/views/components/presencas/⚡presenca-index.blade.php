<?php

use Livewire\Component;
use App\Models\Presenca;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

     public function rendering($view){
        $view->title('Presenças');
    }

    public function with() {
        return [
            // Carregamos os relacionamentos para evitar o problema N+1 no MariaDB
            'presencas' => Presenca::with(['inscricao.evento', 'inscricao.participante'])
                ->latest('data_checkin')
                ->paginate(10),
        ];
    }
}; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">Controlo de Presenças</flux:heading>

        @canany(['criar_presencas'])
        <flux:button href="{{ route('presencas.create') }}" variant="primary">
            Novo Check-in
        </flux:button>
        @endcanany
    </div>
    <flux:separator class="my-4" />

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                <tr>
                    <th scope="col" class="px-6 py-3">Participante</th>
                    <th class="px-6 py-3">Evento</th>
                    <th class="px-6 py-3">Horário de Entrada</th>
                    <th class="px-6 py-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presencas as $presenca)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-700">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $presenca->inscricao->participante->name }}
                        </td>
                        <td class="px-6 py-4">{{ $presenca->inscricao->evento->titulo }}</td>
                        <td class="px-6 py-4">{{ $presenca->data_checkin }}</td>
                        <td class="px-6 py-4">
                             <flux:button href="{{ route('presencas.edit', $presenca->id) }}" size="sm" variant="ghost">Editar</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $presencas->links() }}</div>
</div>