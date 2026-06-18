<?php

use Livewire\Component;
use App\Models\Role;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;
    public $search = '';

    // Reinicia a paginação quando a busca muda
    public function updatingSearch()
    {
        $this->resetPage();
    }

     public function rendering($view){
        $view->title('Papeis');
    }

    public function deleteRole($roleId){

        $role = Role::findOrFail($roleId);
        $role->delete();
    }

    public function with()
    {
        
        return  [
            'roles' => Role::where('name', 'like', '%' . $this->search . '%')
                ->paginate(5),
    ];
    }
};
?>

 
 <div class="p-6 w-full">
    <div class="flex justify-between mb-4">
        <div>
            <flux:heading size="xl">Papeis</flux:heading>
            <flux:subheading size="lg"> Lista de Papeis</flux:subheading>    
        </div> 
        <div class="flex items-center gap-3">
            <!-- 3. O CAMPO DE BUSCA AQUI -->
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                icon="magnifying-glass" 
                placeholder="Pesquisar papel..." 
                class="max-w-xs"
            />

            @can(['criar_roles', 'criar_qualquer_roles'])
            <flux:button href="{{ route('roles.create') }}" icon:trailing="arrow-up-right">
                Novo Papel
            </flux:button>
            @endcan
        </div>
    </div>
    <flux:separator class="my-4" />  

<div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <table class="w-full text-sm text-left rtl:text-right text-body">
        <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Nome
                </th>
                <th scope="col" class="px-6 py-3">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700 border-gray-700">
               
                <td class="px-6 py-4">
                    {{ $role->name }}
                </td>
            
                <td class="px-6 py-4">
                    @can(['editar_roles', 'editar_qualquer_roles'])
                    <flux:button href="{{ route('roles.edit', $role->id) }}" size="sm" variant="primary" color="green" icon:trailing="pencil">
                        Editar
                    </flux:button>
                    @endcan
                    @can(['eliminar_roles', 'eliminar_qualquer_roles'])
                    <flux:button wire:click="deleteRole({{ $role->id }})" size="sm" variant="danger" wire:confirm="Confirmar  exclusão do Usuário_" color="red" icon:trailing="trash">
                        Deletar
                    </flux:button>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $roles->links() }}
</div>
 </div>