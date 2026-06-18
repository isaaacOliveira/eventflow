<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Role;


new class extends Component
{
    public User $user;
    public string $name= '';
    public string $email= '';
    public string|null $password=null;
    public $selectedRoles = [];
    public $allRoles = []; 

   public function rules(): array
   {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255',Rule::unique('users', 'email')->ignore($this->user->id)],
            'password' => ['sometimes','nullable','string','min:8'],
   ];
   }

   public function mount(User $user)
   {
        $this->user = $user;
        $this->name =$user->name;
        $this->email =$user->email;
        $this->selectedRoles = $user->roles()->pluck('name')->toArray();
        $this->allRoles = Role::whereNot('name', 'super_admin')->pluck('name')->toArray();
   }

   public function updateUser()
   {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => empty($this->password) ? $this->user->password : bcrypt($this->password),
        ]);

        $this->user->syncRoles($this->selectedRoles);
        //session()->flash('message', 'Usuário criado com sucesso!');

        $this->reset(['name', 'email', 'password']);
        return redirect()->route('users.index');
   }

};
?>
<div>
    <flux:heading size="xl">Editar Usuário</flux:heading>
    <flux:subheading size="lg">Editar dados de um usuário</flux:subheading>
    <flux:separator class="my-4" /> 
    <section class="w-full md:w-1/2 lg:w-1/3">
        <form wire:submit="updateUser" class="flex flex-col gap-6">
            <!--nome-->
            <flux:input name="name" wire:model="name" :label="__('Nome')" type="text" aria-autocomplete="name" placeholder="Digite o nome completo" required />

            <!--email-->
            <flux:input name="email" wire:model="email" :label="__('Email')" type="email" autocomplete="email" placeholder="email@example.com" required/>
 
            <!--password-->
            <flux:input name="password" wire:model="password" :label="__('Senha')" type="password" placeholder="Digite a senha" viewable />

            <!--papel-->
               <flux:checkbox.group wire:model="selectedRoles" :label="__('Role ')" class="flex flex-wrap space-x-4">
                <flux:checkbox.all label="Selecionar Todas" />
                <flux:separator class="my-2" />    
                @foreach ($allRoles as $role)
                        <div class="bg-gray-300 rounded-md px-3 py-2 mb-2">
                        <flux:checkbox label="{{ $role}}" value="{{ $role}}"/>
                        </div>
                    @endforeach
                </flux:checkbox.group>

            <div>
                @canany(['editar_users', 'editar_qualquer_users'])
                <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                    Salvar
                </flux:button>
                @endcanany
            </div>

        </form>
        


    </section>
</div>