<?php

use Livewire\Component;
use App\Models\Inscricao;
use App\Models\Presenca;

new class extends Component {
    public string $codigo_qr = '';

public function registrarCheckin()
{
    // 1. Procurar a inscrição pelo QR
    $inscricao = Inscricao::where('codigo_qr', $this->codigo_qr)->first();

    if (!$inscricao) {
        session()->flash('error', '⚠️ Código QR inválido. Tente novamente.');
        return;
    }

    // 2. Verificar se já existe presença
    $presencaExistente = Presenca::where('inscricao_id', $inscricao->id)->exists();

    if ($presencaExistente) {
        session()->flash('error', '🚫 Este participante já fez check-in!');
        
        $this->codigo_qr = '';
        return;
    }

    // 3. Criar presença
    Presenca::create([
        'inscricao_id' => $inscricao->id,
        'data_checkin' => now()
    ]);

    session()->flash('success', '✅ Check-in realizado com sucesso!');

    $this->codigo_qr = '';

    return redirect()->route('presencas.index'); 
}
}; ?>

<div wire:poll.5s >
    <flux:heading size="xl">Check-in de Evento</flux:heading>
    <flux:subheading>Registe a entrada do participante através do Código QR</flux:subheading>
    <flux:separator class="my-4" />

    @if (session()->has('success'))
    <div class="bg-green-500 text-white p-4 rounded mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="bg-red-500 text-white p-4 rounded mb-4 text-center animate-pulse">
        {{ session('error') }}
    </div>
@endif
    <section class="max-w-md">
        <div id="reader" style="width: 300px;"></div>
        <form wire:submit="registrarCheckin" class="space-y-6">
            <flux:input 
                wire:model="codigo_qr" 
                label="Código QR" 
                placeholder="Introduza ou digitalize o código..." 
                autofocus 
            />
            @canany(['criar_presencas'])
            <flux:button type="submit" variant="primary" class="w-full">
                Confirmar Presença
            </flux:button>
            @endcanany
        </form>
    </section>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
function onScanSuccess(decodedText) {
    // coloca o valor no Livewire
    @this.set('codigo_qr', decodedText);

    // chama o método automaticamente
    @this.call('registrarCheckin');   
} 

function onScanError(errorMessage) {
    // pode ignorar erros de leitura contínua
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 10, qrbox: 250 }
);

html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>
    
</div>