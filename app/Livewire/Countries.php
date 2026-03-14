<?php

namespace App\Livewire;

use App\Models\Country;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Countries extends Component
{
    public $isModalOpen = false;
    public $country_id, $name, $code, $phone_code, $currency;

    public function render()
    {
        return view('livewire.countries');
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
        $this->code = '';
        $this->phone_code = '';
        $this->currency = '';
        $this->country_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|max:191',
            'code' => 'required|max:191',
            'phone_code' => 'required|max:191',
            'currency' => 'required|max:191',
        ]);

        Country::updateOrCreate(['id' => $this->country_id], [
            'name' => $this->name,
            'code' => $this->code,
            'phone_code' => $this->phone_code,
            'currency' => $this->currency,
        ]);

        session()->flash('message', $this->country_id ? 'Country Updated Successfully.' : 'Country Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-CountryTable');
    }

    #[\Livewire\Attributes\On('edit-country')]
    public function edit($countryId)
    {
        $country = Country::findOrFail($countryId);
        $this->country_id = $countryId;
        $this->name = $country->name;
        $this->code = $country->code;
        $this->phone_code = $country->phone_code;
        $this->currency = $country->currency;

        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-country')]
    public function delete($countryId)
    {
        Country::find($countryId)->delete();
        session()->flash('message', 'Country Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-CountryTable'); 
    }
}
