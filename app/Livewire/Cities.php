<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\State;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Cities extends Component
{
    public $isModalOpen = false;
    public $city_id, $state_id, $name;
    public $states = [];

    public function mount()
    {
        $this->states = State::all();
    }

    public function render()
    {
        return view('livewire.cities');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->states = State::all();
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
        $this->state_id = '';
        $this->city_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:191',
            'state_id' => 'required|exists:states,id',
        ]);

        City::updateOrCreate(['id' => $this->city_id], [
            'name' => $this->name,
            'state_id' => $this->state_id,
        ]);

        session()->flash('message', $this->city_id ? 'City Updated Successfully.' : 'City Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-CityTable');
    }

    #[\Livewire\Attributes\On('edit-city')]
    public function edit($cityId)
    {
        $city = City::findOrFail($cityId);
        $this->city_id = $cityId;
        $this->name = $city->name;
        $this->state_id = $city->state_id;

        $this->states = State::all();
        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-city')]
    public function delete($cityId)
    {
        City::find($cityId)->delete();
        session()->flash('message', 'City Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-CityTable'); 
    }
}
