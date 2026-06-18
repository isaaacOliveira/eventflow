<?php

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

     public function rendering($view){
        $view->title('Usuários');
    }


    public function deleteUser($userId){
        if (! auth()->user()->can('eliminar_users')) {
        abort(403, 'Ação não autorizada.');
    }
        $user = User::findOrFail($userId);
        $user->delete();
    }

    public function with()
    {
        
        return  [
            'users' => User::latest()->paginate(10),
    ];
    }
};
?>

 
 <div class="p-6 w-full">
    <div class="flex justify-between mb-4">
        <div>
            <flux:heading size="xl"> Usuários Cadastrados</flux:heading>
            <flux:subheading size="lg"> Tabela de Usuários</flux:subheading>    
        </div> 
        <div>

            @canany(['criar_users', 'criar_qualquer_users'])
            <flux:button  href="{{ route('users.create') }}" icon:trailing="arrow-up-right">
                Novo Usuário
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
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Nome
                </th>
                <th scope="col" class="px-6 py-3">
                    Email
                </th>
                {{-- papel --}}
                <th>
                    Papel
                </th>
                <th scope="col" class="px-6 py-3">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $user->id }}
                </th>
                <td class="px-6 py-4">
                    {{ $user->name }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->email }}
                </td>

                    <td class="px-6 py-4">
                        @foreach ($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-200 rounded-full">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                <td class="px-6 py-4">
                    @canany(['editar_users', 'editar_qualquer_users'])
                    <flux:button href="{{ route('users.edit', $user->id) }}" size="sm" variant="primary" color="green" icon:trailing="pencil">
                        Editar
                    </flux:button>
                    @endcanany
                    @canany(['eliminar_users'])
                    <flux:button wire:click="deleteUser({{ $user->id }})" size="sm" variant="danger" wire:confirm="Confirmar  exclusão do Usuário_" color="red" icon:trailing="trash">
                        Deletar
                    </flux:button>
                    @endcanany
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

        <div class="p-4">
            {{ $users->links() }}
</div>
 </div>