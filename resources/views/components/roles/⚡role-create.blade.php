<?php

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;



new class extends Component
{
    public $name;
    public $selectedPermissions = [];
    public $allPermissions = [];


    public function mount()
    {
        $this->allPermissions = Permission::pluck('name')->toArray();
    }

   protected function rules()
   {
        return [
            'name' => 'required|string|unique:roles,name',
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'string|exists:permissions,name',
        ];
   }

   public function createRole()
   {
        $this->validate();

       $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web',
        
        ]);
        $role->syncPermissions($this->selectedPermissions);

        $this->name = '';
        $this->selectedPermissions = []; 
        return redirect()->route('roles.index');
   }

};
?>

<div>
    <flux:heading size="xl">Novo Papel</flux:heading>
    <flux:subheading size="lg">Cria um novo papel</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full">
        <form wire:submit="createRole" class="flex flex-col gap-6">
            <!--nome-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" autofocus autocomplete="name" placeholder="Digite o papel" class="w-md" />

            <!--Permissões-->
            <!--Permissões-->
            
                <flux:checkbox.group wire:model="selectedPermissions" :label="__('Permissões')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todas" />
                <flux:separator class="my-2" />    
                @foreach ($allPermissions as $permission)
                        {{-- Aqui $permission já é a string com o nome da permissão --}}
                        <div class="rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $permission}}" value="{{ $permission }}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>
                

            <div>
                @can(['criar_roles', 'criar_qualquer_roles'])
                <flux:button type="submit" variant="primary" class="w-md cursor-pointer">
                    Criar Papel
                </flux:button>
                @endcan
            </div>

        </form>
        


    </section>
</div>