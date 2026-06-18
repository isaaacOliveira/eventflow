<?php

use Livewire\Component;
use App\Models\Evento;
use Livewire\WithFileUploads; // Adicionado
use Illuminate\Support\Facades\Storage; // Adicionado

new class extends Component
{
use WithFileUploads; // Adicionado

public Evento $evento;

public string $titulo = '';
public string $descricao = '';
public string $local = '';
public $data_evento = '';
public $data_fim = '';
public int $vagas_disponiveis = 0;
public int $capacidade_maxima = 0;
public $foto; // Nova foto temporária
public ?string $fotoExistente = null; // Caminho da foto atual

public function mount(Evento $evento)
{
$this->evento = $evento;
$this->titulo = $evento->titulo;
$this->descricao = $evento->descricao;
$this->local = $evento->local;

// Correção de bugs de data caso venham nulas ou strings nativas
$this->data_evento = $evento->data_evento ? (is_string($evento->data_evento) ? date('Y-m-d\TH:i', strtotime($evento->data_evento)) : $evento->data_evento->format('Y-m-d\TH:i')) : '';
$this->data_fim = $evento->data_fim ? (is_string($evento->data_fim) ? date('Y-m-d\TH:i', strtotime($evento->data_fim)) : $evento->data_fim->format('Y-m-d\TH:i')) : '';

$this->vagas_disponiveis = $evento->vagas_disponiveis;
$this->capacidade_maxima = $evento->capacidade_maxima;
$this->fotoExistente = $evento->foto_caminho; // Atribui a foto atual
}

public function atualizar()
{
$this->validate([
'titulo' => 'required|string',
'descricao' => 'required|string',
'local' => 'required|string',
'data_evento' => 'required|date',
'data_fim' => 'required|date|after_or_equal:data_evento',
'vagas_disponiveis' => 'required|integer|min:0',
'capacidade_maxima' => 'required|integer|min:1',
'foto' => 'nullable|image|max:2048',
]);

$fotoCaminho = $this->evento->foto_caminho;

// Se uma nova foto foi submetida
if ($this->foto) {
// Apagar a antiga se existir
if ($this->evento->foto_caminho && Storage::disk('public')->exists($this->evento->foto_caminho)) {
Storage::disk('public')->delete($this->evento->foto_caminho);
}
// Guardar a nova
$fotoCaminho = $this->foto->store('eventos', 'public');
}

$this->evento->update([
'titulo' => $this->titulo,
'descricao' => $this->descricao,
'local' => $this->local,
'data_evento' => $this->data_evento,
'data_fim' => $this->data_fim,
'vagas_disponiveis' => $this->vagas_disponiveis,
'capacidade_maxima' => $this->capacidade_maxima,
'foto_caminho' => $fotoCaminho,
]);

return redirect()->route('eventos.index')
->with('success', 'Evento atualizado com sucesso!');
}
};
?>

<div class="p-6 max-w-2xl mx-auto">

<flux:heading size="xl">Editar Evento</flux:heading>
<flux:separator class="my-4" />

<div class="space-y-4">

<flux:input wire:model="titulo" label="Título do Evento" class="w-full" />
<flux:textarea wire:model="descricao" label="Descrição do Evento" type="textarea" class="w-full" />

{{-- Upload de Imagem na Edição --}}
<div class="space-y-2">
<label class="block text-sm font-medium text-gray-700">Foto do Evento</label>

@if ($fotoExistente && !$foto)
<div class="mb-2">
<p class="text-xs text-gray-500 mb-1">Foto Atual:</p>
<img src="{{ asset('storage/' . $fotoExistente) }}" class="w-40 h-24 object-cover rounded-lg border">
</div>
@endif

<input type="file" wire:model="foto" class="w-full p-2 border rounded">

@if ($foto)
<div class="mt-2">
<p class="text-xs text-gray-500 mb-1">Nova foto selecionada:</p>
<img src="{{ $foto->temporaryUrl() }}" class="w-40 h-24 object-cover rounded-lg border">
</div>
@endif
@error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>

<flux:input wire:model="local" label="Local do Evento" class="w-full" />

<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
<input type="datetime-local" wire:model="data_evento" class="w-full p-2 border rounded">
</div>
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">Data de Fim</label>
<input type="datetime-local" wire:model="data_fim" class="w-full p-2 border rounded">
</div>
</div>

<flux:input wire:model="vagas_disponiveis" label="Vagas Disponíveis" type="number" class="w-full" />
<flux:input wire:model="capacidade_maxima" label="Capacidade Máxima" type="number" class="w-full" />

@canany(['editar_eventos'])
<flux:button wire:click="atualizar" variant="primary" class="w-full">
Atualizar Evento
</flux:button>
@endcanany

</div>
</div>
