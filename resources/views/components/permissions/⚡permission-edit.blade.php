<?php

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;

new class extends Component
{
    public Permission $permission; 
    public $name;
    public $selectedRoles = []; 
    public $allRoles = [];

   public function mount(Permission $permission)
    {
        $this->permission = $permission;
        
        // CORREÇÃO: Atribuir o nome da permissão para a variável que o wire:model usa
        $this->name = $permission->name;
        
        // CORREÇÃO: Carregar os papéis vinculados a esta permissão
        $this->selectedRoles = $permission->roles()->pluck('name')->toArray();
        
        // CORREÇÃO: Carregar todos os papéis disponíveis para a lista
        $this->allRoles = Role::whereNot('name', 'super_admin')->pluck('name')->toArray();
    }

   protected function rules()
   {
      return [
            // Garante que o nome seja único, ignorando o ID da permissão atual
            'name' => ['required', 'string', "unique:permissions,name,{$this->permission->id}"],
            'selectedRoles' => 'array',
        ];
   }

   public function updatePermission()
   {
        $this->validate();

         $this->permission->update([
                'name' => $this->name,
               
          
          ]);

          $this->permission->syncRoles($this->selectedRoles);

        $this->name = '';
        $this->selectedPermissions = []; 
        return redirect()->route('permissions.index');
   }
};
?>

<div>
    <flux:heading size="xl">Editar Permissão</flux:heading>
    <flux:subheading size="lg">Editar dados de um permissão</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full md:w-1/2 lg:w-1/3">
        <form wire:submit="updatePermission" class="flex flex-col gap-6">
            <!--papel-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" aria-autocomplete="name" placeholder="Digite o nome da permissão" required />

            
             <!--Permissões-->
                <flux:checkbox.group wire:model="selectedRoles" :label="__('Role ')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todas" />
                <flux:separator class="my-2" />    
                @foreach ($allRoles as $role)
                        {{-- Aqui $permission já é a string com o nome da permissão --}}
                        <div class="rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $role}}" value="{{ $role }}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>

            <div>
                @canany(['editar_permissions', 'editar_qualquer_permissions'])
                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Salvar
                </flux:button>
                @endcanany
            </div>

        </form>
        


    </section>
</div>