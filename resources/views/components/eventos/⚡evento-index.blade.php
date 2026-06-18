<?php

use Livewire\Component;
use App\Models\Evento;
use App\Models\Inscricao;
use Illuminate\Support\Str;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function rendering($view){
        $view->title('Eventos');
    }

    public function inscrever($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);

        if ($evento->data_fim && \Carbon\Carbon::parse($evento->data_fim)->isPast()) {
            session()->flash('erro', 'Este evento já foi encerrado.');
            return;
        }

        if ($evento->vagas_disponiveis <= 0) {
            session()->flash('erro', 'Sem vagas disponíveis.');
            return;
        }

        $existe = Inscricao::where('participante_id', auth()->id())
            ->where('evento_id', $eventoId)
            ->exists();

        if ($existe) {
            session()->flash('erro', 'Já estás inscrito neste evento.');
            return;
        }

        Inscricao::create([
            'participante_id' => auth()->id(),
            'evento_id' => $eventoId,
            'codigo_qr' => 'QR-' . strtoupper(Str::random(10)) . '-' . date('Y'),
            'data_inscricao' => now(),
        ]);

        $evento->decrement('vagas_disponiveis');
        session()->flash('success', 'Inscrição realizada com sucesso!');
    }

    public function deleteEvento($eventoId)
    {
        if (! auth()->user()->can('excluir_eventos')) {
            abort(403, 'Ação não autorizada.');
        }

        $evento = Evento::findOrFail($eventoId);
        $temInscricoes = Inscricao::where('evento_id', $eventoId)->exists();

        if ($temInscricoes) {
            session()->flash('erro', 'Não é possível deletar um evento com inscrições.');
            return;
        }

        // Apagar foto do storage antes de deletar o registro
        if ($evento->foto_caminho && \Storage::disk('public')->exists($evento->foto_caminho)) {
            \Storage::disk('public')->delete($evento->foto_caminho);
        }

        $evento->delete();
        session()->flash('success', 'Evento eliminado com sucesso!');
    }

    public function with()
    {
        $userId = auth()->id();

        return [
            'eventos' => Evento::query()
                ->leftJoin('inscricoes', function($join) use ($userId) {
                    $join->on('eventos.id', '=', 'inscricoes.evento_id')
                         ->where('inscricoes.participante_id', '=', $userId);
                })
                ->select('eventos.*')
                ->groupBy('eventos.id') // Garante que o evento só apareça uma vez na lista
                ->orderByRaw('MAX(inscricoes.id) IS NULL DESC') // Ajustado para funcionar com o groupBy
                ->orderByRaw('eventos.data_fim > NOW() DESC')
                ->orderByRaw('eventos.vagas_disponiveis > 0 DESC')
                ->latest('eventos.created_at')
                ->paginate(6),
        ];
    }
};
?>

<div class="p-6 w-full max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <flux:heading size="xl" class="font-extrabold text-gray-900 tracking-tight"> Descubra Eventos Incríveis</flux:heading>
            <flux:subheading size="lg" class="text-gray-500">Reserve o seu lugar nos eventos mais exclusivos do momento.</flux:subheading>
        </div>
        <div>
            @canany(['criar_eventos'])
            <flux:button href="{{ route('eventos.create') }}" variant="primary" class="shadow-md hover:shadow-lg transition-all duration-200">
                 Criar Novo Evento
            </flux:button>
            @endcanany
        </div>
    </div>

    <flux:separator class="my-6" />

    {{-- ALERTAS --}}
    @if (session('success'))
    <div class="bg-emerald-500 text-white p-3 rounded-xl shadow-md mb-6 flex items-center gap-2 font-medium animate-fade-in">
         {{ session('success') }}
    </div>
    @endif

    @if (session('erro'))
    <div class="bg-rose-500 text-white p-3 rounded-xl shadow-md mb-6 flex items-center gap-2 font-medium animate-fade-in">
         {{ session('erro') }}
    </div>
    @endif

    {{-- GRID DE EVENTOS MODERNO --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($eventos as $evento)
            @php
                $jaInscrito = $evento->inscricoes()->where('participante_id', auth()->id())->exists();
                $encerrado = $evento->data_fim && \Carbon\Carbon::parse($evento->data_fim)->isPast();
            @endphp

            <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">

                {{-- Imagem de Capa do Evento --}}
                <div class="relative w-full h-48 bg-gray-100 overflow-hidden">
                    @if($evento->foto_caminho)
                        <img src="{{ asset('storage/' . $evento->foto_caminho) }}" alt="{{ $evento->titulo }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        {{-- Placeholder moderno e abstrato baseado em gradiente caso não tenha imagem --}}
                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center">
                            <span class="text-white font-bold tracking-widest text-lg opacity-70">EVENTO</span>
                        </div>
                    @endif

                    {{-- Badges Flutuantes por cima da Imagem --}}
                    <div class="absolute top-3 right-3">
                        @if ($encerrado)
                            <span class="bg-black/70 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full font-semibold">Encerrado</span>
                        @elseif ($evento->vagas_disponiveis <= 0)
                            <span class="bg-rose-600 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow-sm">Esgotado </span>
                        @elseif ($jaInscrito)
                            <span class="bg-emerald-600 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow-sm">Confirmado ✓</span>
                        @else
                            <span class="bg-indigo-600 text-white text-xs px-3 py-1.5 rounded-full font-semibold shadow-sm">{{ $evento->vagas_disponiveis }} vagas restantes</span>
                        @endif
                    </div>
                </div>

                {{-- Conteúdo do Card --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1 flex items-center gap-1">
                            <flux:icon name="map-pin"/> {{ $evento->local }}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $evento->titulo }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-3 leading-relaxed">
                            {{ $evento->descricao }}
                        </p>
                    </div>

                    {{-- Metadados Detalhados --}}
                    <div class="pt-4 border-t border-gray-50 text-xs text-gray-600 space-y-2">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-400">Organizado por:</span>
                            <span class="font-semibold text-gray-800">{{ $evento->organizador->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-400">Data de Início:</span>
                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($evento->data_evento)->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    {{-- Secção de Ações --}}
                    <div class="space-y-2 pt-2">
                        @if ($encerrado)
                            <flux:button disabled variant="ghost" class="w-full bg-gray-50 border-gray-200">
                                Evento Terminado
                            </flux:button>
                        @elseif ($evento->vagas_disponiveis <= 0 && !$jaInscrito)
                            <flux:button disabled variant="danger" class="w-full opacity-60">
                                Sem Vagas
                            </flux:button>
                        @elseif ($jaInscrito)
                            <flux:button disabled variant="filled" class="w-full bg-emerald-50 text-emerald-700 border-emerald-200 font-bold">
                                Você já Garantiu seu Ingresso!
                            </flux:button>
                        @else
                            <flux:button wire:click="inscrever({{ $evento->id }})" variant="primary" class="w-full font-bold shadow-sm">
                                Garante seu Ingresso 
                            </flux:button>
                        @endif

                        {{-- Painel Administrativo --}}
                        @if(auth()->user()->can('editar_eventos') || auth()->user()->can('excluir_eventos'))
                            <div class="flex gap-2 mt-2 pt-2 border-t border-dashed border-gray-100">
                                @can('editar_eventos')
                                <flux:button href="{{ route('eventos.edit', $evento->id) }}" variant="ghost" size="sm" class="flex-1 text-gray-600 hover:bg-gray-50">
                                    Editar
                                </flux:button>
                                @endcan

                                @can('excluir_eventos')
                                <flux:button wire:click="deleteEvento({{ $evento->id }})" variant="ghost" size="sm" class="flex-1 text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                                    Eliminar
                                </flux:button>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- PAGINAÇÃO --}}
    <div class="mt-8">
        {{ $eventos->links() }}
    </div>
</div>
