<?php

namespace App\Livewire;

use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Roles extends Component
{
    public $isModalOpen = false;
    public $role_id, $name, $status = 'Y';

    public function render()
    {
        return view('livewire.roles');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->status = 'Y';
        $this->role_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:191',
            'status' => 'required|in:Y,N',
        ]);

        Role::updateOrCreate(['id' => $this->role_id], [
            'name' => $this->name,
            'status' => $this->status
        ]);

        session()->flash('message', $this->role_id ? 'Role Updated Successfully.' : 'Role Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-RoleTable');
    }

    #[\Livewire\Attributes\On('edit-role')]
    public function edit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->role_id = $roleId;
        $this->name = $role->name;
        $this->status = $role->status;

        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-role')]
    public function delete($roleId)
    {
        Role::find($roleId)->delete();
        session()->flash('message', 'Role Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-RoleTable'); 
    }
}
