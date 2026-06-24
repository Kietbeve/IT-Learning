<?php

namespace Modules\Learning\Livewire\Admin\Collaborators;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;

#[Layout('learning::layouts.master')]
class Edit extends Component
{
    public int $collaboratorId;
    public string $name = '';
    public string $email = '';

    public function mount(int $id)
    {
        $user = User::findOrFail($id);
        
        $this->collaboratorId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->collaboratorId,
        ]);

        $user = User::findOrFail($this->collaboratorId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'Cập nhật thông tin thành công!');
        
        return redirect()->to('/admin/collaborators');
    }

    public function render()
    {
        return view('learning::livewire.admin.collaborators.edit');
    }
}