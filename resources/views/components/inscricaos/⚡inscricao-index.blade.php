<?php

use Livewire\Component;
use App\Models\Inscricao;
use Livewire\WithPagination;

new class extends Component
{   
    use WithPagination;

    public function rendering($view){
        $view->title('Inscrições');
    }

    public function deletarInscricao($inscricaoId) {
        $inscricao = Inscricao::findOrFail($inscricaoId);
        
        // Devolve a vaga ao evento antes de excluir
        if ($inscricao->evento) {
            $inscricao->evento->increment('vagas_disponiveis');
        }

        $inscricao->delete();
    }

    public function with() {
        return [
            // Filtra apenas as inscrições do usuário logado
            'inscricaos' => Inscricao::where('participante_id', auth()->id())
                ->with(['evento', 'presenca'])
//               ->whereDoesntHave('presenca')
                ->latest()
                ->paginate(6),
        ];
    }
};
?>
<div wire:poll.5s class="p-6 w-full">
    <div class="flex justify-between mb-4">
        <div>
    <flux:heading size="xl">Meus Ingressos</flux:heading>
    <flux:subheading size="lg">Lista de Eventos</flux:subheading>
        </div>
        <div>
    @canany(['visualizar_eventos'])
    <flux:button  href="{{ route('eventos.index') }}" icon:trailing="arrow-up-right">
                Novos Ingressos
    </flux:button>
    @endcanany
        </div>
    
    </div>
    <flux:separator class="my-4" />
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($inscricaos as $inscricao)
        {{-- Se houver presença, podemos esconder o cartão usando Alpine.js para ser instantâneo --}}
        <flux:card class="space-y-4">
    <div>
        <flux:heading size="lg">{{ $inscricao->evento->titulo }}</flux:heading>
        <flux:subheading>{{ $inscricao->evento->local }}</flux:subheading>
    </div>

    @if($inscricao->presenca)
        {{-- ESTADO: PRESENÇA CONFIRMADA --}}
        {{-- Aqui não mostramos o botão de cancelar nem o QR --}}
        <div class="p-4 bg-green-500/10 border border-green-500/50 rounded-lg text-center">
            <flux:icon.check-circle class="mx-auto text-green-500 mb-2" />
            <flux:text color="green" variant="strong">Check-in Concluído!</flux:text>
            <flux:subheading size="sm">Aproveite o evento.</flux:subheading>
        </div>
   @else
    {{-- ESTADO: PENDENTE --}}
    <div class="flex flex-col items-center space-y-4 w-full">
        <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-center">
            @php
                try {
                    $options = new \chillerlan\QRCode\QROptions([
                        'version'      => 5,
                        'outputType'   => 'png', // Mudamos para PNG para ser mais estável
                        'eccLevel'     => \chillerlan\QRCode\Common\EccLevel::L,
                        'scale'        => 5,
                        'addQuietzone' => true,
                    ]);

                    // Geramos a imagem PNG em formato Base64
                    $qrImage = (new \chillerlan\QRCode\QRCode($options))->render($inscricao->codigo_qr);
                } catch (\Exception $e) {
                    $qrImage = null;
                }
            @endphp
            
            @if($qrImage)
                {{-- Aqui usamos uma tag <img> normal, que não sofre conflito com o CSS do SVG --}}
                <img src="{{ $qrImage }}" alt="QR Code" class="size-32 md:size-40 shadow-inner">
            @else
                <div class="size-40 flex items-center justify-center text-red-500 text-xs border border-dashed border-red-200">
                    Erro ao processar QR
                </div>
            @endif
        </div>
        
        <div class="text-center">
            <flux:text class="text-[10px] font-mono text-zinc-400 uppercase tracking-tighter">
                Código de Ingresso
            </flux:text>
            <flux:text variant="strong" class="text-xs font-mono block">
                {{ $inscricao->codigo_qr }}
            </flux:text>
        </div>
        
        <flux:button 
            wire:click="deletarInscricao({{ $inscricao->id }})" 
            wire:confirm="Queres cancelar esta inscrição?" 
            variant="danger" 
            size="sm" 
            class="w-full"
        >
            Cancelar Ingresso
        </flux:button>
    </div>
@endif
</flux:card>
    @endforeach
</div>
    <div class="mt-4">{{ $inscricaos->links() }}</div>
</div>