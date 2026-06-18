<?php

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;



new class extends Component
{
    public $name;
    public $selectedRoles = [];
    public $allRoles = [];


    public function mount()
    { 
        $this->allRoles = Role::whereNot('name', 'super_admin')->pluck('name')->toArray();
    }

   protected function rules()
   {
        return [
            'name' => 'required|string|unique:permissions,name',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'string|exists:roles,name',
        ];
   }

   public function createPermission()
   {
        $this->validate();

       $permission = Permission::create([
            'name' => $this->name,
            'guard_name' => 'web',
        
        ]); 
        $permission->syncRoles($this->selectedRoles);

        $this->name = '';
        $this->selectedPermissions = []; 
        return redirect()->route('permissions.index');
   }

};
?>

<div>
    <flux:heading size="xl">Nova Permissão</flux:heading>
    <flux:subheading size="lg">Cria uma nova Permissão</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full md:w-1/2 lg:w-1/3">
        <form wire:submit="createPermission" class="flex flex-col gap-6">
            <!--nome-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" autofocus autocomplete="name" placeholder="Digite a permissão" class="w-md" />

            
            <!--papel-->
               <flux:checkbox.group wire:model="selectedRoles" :label="__('Role ')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todas" />
                <flux:separator class="my-2" />    
                @foreach ($allRoles as $role)
                        <div class="rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $role}}" value="{{ $role}}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>
                

            <div>
                @canany(['criar_permissions', 'criar_qualquer_permissions'])
                <flux:button type="submit" variant="primary" class="w-md cursor-pointer">
                    Salvar
                </flux:button>
                @endcanany
            </div>

        </form>
        


    </section>
</div>