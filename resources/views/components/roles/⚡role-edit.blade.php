<?php

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;

new class extends Component
{
    public Role $role;
    public $name;
    public $selectedPermissions = [];
    public $allPermissions = [];


    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions()->pluck('name')->toArray();
        $this->allPermissions = Permission::pluck('name')->toArray();
    }

   protected function rules()
   {
      return [
            // Corrigido: adicionada a vírgula para ignorar o ID atual na validação unique
            'name' => ['required', 'string', "unique:roles,name,{$this->role->id}"],
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'string|exists:permissions,name',
        ];
   }

   public function updateRole()
   {
        $this->validate();

         $this->role->update([
                'name' => $this->name,
                'guard_name' => 'web',
          
          ]);
        $this->role->syncPermissions($this->selectedPermissions);

        $this->name = '';
        $this->selectedPermissions = []; 
        return redirect()->route('roles.index');
   }
};
?>

<div>
    <flux:heading size="xl">Editar Papel</flux:heading>
    <flux:subheading size="lg">Editar dados de um papel</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full ">
        <form wire:submit="updateRole" class="flex flex-col gap-6">
            <!--papel-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" aria-autocomplete="name" placeholder="Digite o papel" required />

            
             <!--Permissões-->
                <flux:checkbox.group wire:model="selectedPermissions" :label="__('Permissões')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todas" />
                <flux:separator class="my-2" />    
                @foreach ($allPermissions as $permission)
                        {{-- Aqui $permission já é a string com o nome da permissão --}}
                        <div class="rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $permission}}" value="{{ $permission}}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>

            <div>
                @can(['editar_roles', 'editar_qualquer_roles'])
                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Salvar
                </flux:button>
                @endcan
            </div>

        </form>
        


    </section>
</div>