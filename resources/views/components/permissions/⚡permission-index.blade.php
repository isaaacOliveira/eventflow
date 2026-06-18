<?php

use Livewire\Component;
use App\Models\Permission;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

     public function rendering($view){
        $view->title('Permissões');
    }

    public function deletePermission($permissionId){ 

        $permission = Permission::findOrFail($permissionId);
        $permission->delete();
    }

    public function with()
    {
        
        return  [
            'permissions' => Permission::latest()->paginate(6),
    ];
    }
};
?>

 
 <div class="p-6 w-full">
    <div class="flex justify-between mb-4">
        <div>
            <flux:heading size="xl"> Permissões</flux:heading>
            <flux:subheading size="lg"> Lista de Permissão</flux:subheading>    
        </div> 
        <div>
            @canany(['criar_permissions', 'criar_qualquer_permissions'])
            <flux:button  href="{{ route('permissions.create') }}" icon:trailing="arrow-up-right">
                Nova Permissão
            </flux:button>
            @endcanany
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
            @foreach ($permissions as $permission)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-700">
                
                <td class="px-6 py-4">
                    {{ $permission->name }}
                </td>
            
                <td class="px-6 py-4">
                    @canany(['editar_permissions', 'editar_qualquer_permissions'])
                    <flux:button href="{{ route('permissions.edit', $permission->id) }}" size="sm" variant="primary" color="green" icon:trailing="pencil">
                        Editar
                    </flux:button>
                    @endcanany
                    @canany(['eliminar_permissions', 'eliminar_qualquer_permissions'])
                    <flux:button wire:click="deletePermission({{ $permission->id }})" size="sm" variant="danger" wire:confirm="Confirmar  exclusão da permissao" color="red" icon:trailing="trash">
                        Deletar
                    </flux:button>
                    @endcanany
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $permissions->links() }}

</div>
 </div>