<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Role;


new class extends Component
{
    public $name;
    public $email;
    public $password;
    public $selectedRoles = [];
    public $allRoles = []; 

    public function mount()
    { 
        $this->allRoles = Role::whereNot('name', 'super_admin')->pluck('name')->toArray();
    } 

   public function rules(): array
   {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8'],
   ];
   }

   public function createUser()
   {
        $this->validate();

       $newUser = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        if(!empty($this->selectedRoles)){
            $newUser->assignRole($this->selectedRoles);
        }

        session()->flash('message', 'Usuário criado com sucesso!');
        $this->reset(['name', 'email', 'password']);
        return redirect()->route('users.index');
   }

};
?>

<div>
    <flux:heading size="xl">Novo Usuário</flux:heading>
    <flux:subheading size="lg">Criar novo usuário</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full md:w-1/2 lg:w-1/3">
        <form wire:submit="createUser" class="flex flex-col gap-6">
            <!--nome-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" aria-autocomplete="name" placeholder="Digite o nome completo" required />

            <!--email-->
            <flux:input name="email" wire:model="email" :label="__('Email')" type="email" autocomplete="email" placeholder="email@example.com" required/>
 
            <!--password-->
            <flux:input name="password" wire:model="password" :label="__('Senha')" type="password" placeholder="Digite a senha" viewable required/>

            <!--papel-->
               <flux:checkbox.group wire:model="selectedRoles" :label="__('Papel ')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todos" />
                <flux:separator class="my-2" />    
                @foreach ($allRoles as $role)
                        <div class="bg-gray-300 rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $role}}" value="{{ $role}}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>

            <div>
                @canany(['criar_users', 'criar_qualquer_users'])
                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Criar Usuário
                </flux:button>
                @endcanany
            </div>

        </form>
        


    </section>
</div>