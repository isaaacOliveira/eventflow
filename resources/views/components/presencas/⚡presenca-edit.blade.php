<?php

use Livewire\Component;
use App\Models\Presenca;

new class extends Component {
    public Presenca $presenca;
    public string $data_checkin;

    public function mount(Presenca $presenca) {
        $this->presenca = $presenca;
        $this->data_checkin = \Carbon\Carbon::parse($presenca->data_checkin)->format('Y-m-d\TH:i');
    }

    public function update()
{
    $this->validate([
        'data_checkin' => 'required|date'
    ]);

    $this->presenca->update([
        'data_checkin' => $this->data_checkin
    ]);

    session()->flash('success', 'Presença atualizada com sucesso!');

    return redirect()->route('presencas.index');
}
}; ?>

<div class="max-w-md">
    <flux:heading size="xl">Editar Presença</flux:heading>
    <flux:subheading>Ajustar horário de entrada para: {{ $presenca->inscricao->participante->name }}</flux:subheading>
    <flux:separator class="my-4" />
    
    <form wire:submit="update" class="space-y-6">
        <flux:input 
            type="datetime-local" 
            wire:model="data_checkin" 
            label="Data e Hora do Check-in" 
        />
        
        <div class="flex gap-2">
            <flux:button type="submit" variant="primary">Atualizar</flux:button>
            <flux:button href="{{ route('presencas.index') }}" variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>