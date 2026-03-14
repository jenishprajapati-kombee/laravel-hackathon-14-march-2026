<?php

namespace App\Livewire;

use App\Models\State;
use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class States extends Component
{
    public $isModalOpen = false;
    public $state_id, $country_id, $name;
    public $countries = [];

    public function mount()
    {
        $this->countries = Country::all();
    }

    public function render()
    {
        return view('livewire.states');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->countries = Country::all();
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
        $this->country_id = '';
        $this->state_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:191',
            'country_id' => 'required|exists:countries,id',
        ]);

        State::updateOrCreate(['id' => $this->state_id], [
            'name' => $this->name,
            'country_id' => $this->country_id,
        ]);

        session()->flash('message', $this->state_id ? 'State Updated Successfully.' : 'State Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-StateTable');
    }

    #[\Livewire\Attributes\On('edit-state')]
    public function edit($stateId)
    {
        $state = State::findOrFail($stateId);
        $this->state_id = $stateId;
        $this->name = $state->name;
        $this->country_id = $state->country_id;

        $this->countries = Country::all();
        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-state')]
    public function delete($stateId)
    {
        State::find($stateId)->delete();
        session()->flash('message', 'State Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-StateTable'); 
    }
}
