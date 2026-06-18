<?php

use Livewire\Component;
use App\Models\Inscricao;
use App\Models\Evento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    public Evento $evento;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function realizarInscricao()
    {
        DB::transaction(function () {

            // Atualiza evento com lock (evita overbooking)
            $evento = Evento::lockForUpdate()->find($this->evento->id);

            // 1. Verificar vagas
            if ($evento->vagas_disponiveis <= 0) {
                session()->flash('erro', 'Sem vagas disponíveis.');
                return;
            }

            // 2. Verificar inscrição duplicada
            $existe = Inscricao::where('participante_id', auth()->id())
                ->where('evento_id', $evento->id)
                ->exists();

            if ($existe) {
                session()->flash('erro', 'Você já está inscrito.');
                return;
            }

            // 3. Criar inscrição
            Inscricao::create([
                'participante_id' => auth()->id(),
                'evento_id' => $evento->id,
                'codigo_qr' => 'QR-' . strtoupper(Str::random(10)),
                'data_inscricao' => now(),
            ]);

            // 4. Atualizar vagas
            $evento->decrement('vagas_disponiveis');

        });

        return redirect()->route('inscricaos.index')
            ->with('success', 'Inscrição realizada com sucesso!');
    }
};
?>

<div class="p-6">

    <flux:heading size="xl">Confirmar Inscrição</flux:heading>
    <flux:subheading size="lg">
        Você está prestes a se inscrever no evento: 
        <strong>{{ $evento->titulo }}</strong>
    </flux:subheading>

    <flux:separator class="my-4" /> 

    @if (session('erro'))
        <div class="bg-red-500 text-white p-2 rounded mb-4">
            {{ session('erro') }}
        </div>
    @endif

    <section class="w-full md:w-1/2">
        <div class="bg-gray-800/40 p-6 rounded-xl border border-gray-700">
            <p class="text-white mb-4"><strong>Local:</strong> {{ $evento->local }}</p>
            <p class="text-white mb-4"><strong>Data:</strong> {{ $evento->data_evento }}</p>

            @if($evento->vagas_disponiveis > 0)
                <flux:button wire:click="realizarInscricao" variant="primary" class="w-full">
                    Confirmar Minha Inscrição
                </flux:button>
            @else
                <flux:button variant="danger" disabled class="w-full">
                    Sem vagas disponíveis
                </flux:button>
            @endif
        </div>
    </section>

</div>