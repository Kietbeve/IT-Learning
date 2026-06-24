<?php

namespace Modules\Learning\Livewire\Admin\Collaborators;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;

#[Layout('learning::layouts.master')]
class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ];

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'type' => 'collaborator',
        ]);

        session()->flash('message', 'Thêm mới cộng tác viên thành công!');
        
        return redirect()->to('/admin/collaborators');
    }

    public function render()
    {
        return view('learning::livewire.admin.collaborators.create');
    }
}