<?php

namespace Modules\Learning\Livewire\Admin\Collaborators;

use Livewire\Component;
use Livewire\Attributes\Layout; // Khai báo thư viện Layout
use App\Models\User;

#[Layout('learning::layouts.master')] // Chỉ định Layout trực tiếp tại đây
class Index extends Component
{
    public function deleteCollaborator(int $id) 
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        session()->flash('message', 'Đã xóa cộng tác viên thành công!');
    }

    public function render()
    {
        return view('learning::livewire.admin.collaborators.index', [
            'collaborators' => User::where('type', 'collaborator')->latest()->get()
        ]);
    }
}