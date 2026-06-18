
<?php
use Livewire\Component;
use App\Models\Evento;
use Livewire\WithFileUploads; // Importar a trait para uploads

new class extends Component{
use WithFileUploads; // Utilizar a trait

public string $titulo = '';
public string $descricao = '';
public string $local = '';
public string $data_evento = '';
public string $data_fim = '';
public int $vagas_disponiveis = 0;
public int $capacidade_maxima = 0;
public $foto; // Nova propriedade para armazenar o arquivo temporário

public function salvar()
{
$this->validate([
'titulo' => 'required|string|max:255',
'descricao' => 'required|string',
'local' => 'required|string',
'data_evento' => 'required|date|after:now',
'data_fim' => 'required|date|after_or_equal:data_evento',
'vagas_disponiveis' => 'required|integer|min:0',
'capacidade_maxima' => 'required|integer|min:1',
'foto' => 'nullable|image|max:2048', // Validação da foto (Máx: 2MB)
]);

$fotoCaminho = null;
if ($this->foto) {
// Salva a foto na pasta 'public/eventos' dentro do storage
$fotoCaminho = $this->foto->store('eventos', 'public');
}

Evento::create([
'titulo' => $this->titulo,
'descricao' => $this->descricao,
'local' => $this->local,
'data_evento' => $this->data_evento,
'data_fim' => $this->data_fim,
'capacidade_maxima' => $this->capacidade_maxima,
'vagas_disponiveis' => $this->capacidade_maxima,
'organizador_id' => auth()->id(),
'foto_caminho' => $fotoCaminho, // Guardar o caminho
]);

return redirect()->route('eventos.index')
->with('success', 'Evento criado com sucesso!');
}};?>

<div class="p-6 max-w-2xl mx-auto">
<flux:heading size="xl">Criar Evento</flux:heading>
<flux:separator class="my-4" />

<div class="space-y-4">
<flux:input wire:model="titulo" label="Título do Evento" placeholder="Digite o título do evento" />
<flux:textarea wire:model="descricao" label="Descrição do Evento" placeholder="Digite a descrição do evento" />


<div class="space-y-2">
<label class="block text-sm font-medium text-gray-700">Foto do Evento</label>
<input type="file" wire:model="foto" class="w-full p-2 border rounded">
<div wire:loading wire:target="foto" class="text-sm text-gray-500">A carregar imagem...</div>

@if ($foto)
<div class="mt-2">
<p class="text-xs text-gray-500 mb-1">Pré-visualização:</p>
<img src="{{ $foto->temporaryUrl() }}" class="w-full h-40 object-cover rounded-lg border">
</div>
@endif
@error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>

<flux:input wire:model="local" label="Local do Evento" placeholder="Digite o local do evento" />
<flux:input wire:model="data_evento" type="datetime-local" label="Data do Evento" />
<flux:input wire:model="data_fim" type="datetime-local" label="Data de Término do Evento" />
<flux:input wire:model="capacidade_maxima" min="0" type="number" label="Capacidade Máxima" placeholder="Digite a capacidade máxima do evento" />
<flux:input wire:model="vagas_disponiveis" min="0" type="number" label="Vagas Disponíveis" placeholder="Digite o número de vagas disponíveis" />

@canany(['criar_eventos'])
<flux:button wire:click="salvar" variant="primary" class="w-full">
Criar Evento
</flux:button>
@endcanany
</div>
</div>