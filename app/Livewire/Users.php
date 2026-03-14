<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Users extends Component
{
    public $isModalOpen = false;
    public $user_id, $name, $email, $password, $role_id, $dob, $mobile_number, $country_id, $state_id, $city_id, $gender, $status = 'Y';
    public $roles = [], $countries = [], $states = [], $cities = [];

    public function mount()
    {
        $this->roles = Role::all();
        $this->countries = Country::all();
        $this->states = State::all();
        $this->cities = City::all();
    }

    public function render()
    {
        return view('livewire.users');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->mount();
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
        $this->user_id = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_id = '';
        $this->dob = '';
        $this->mobile_number = '';
        $this->country_id = '';
        $this->state_id = '';
        $this->city_id = '';
        $this->gender = '';
        $this->status = 'Y';
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max:191',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role_id' => 'required|exists:roles,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'status' => 'required|in:Y,N',
            'gender' => 'required|in:M,F',
        ];
        
        if (!$this->user_id) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'dob' => $this->dob,
            'mobile_number' => $this->mobile_number,
            'gender' => $this->gender,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', $this->user_id ? 'User Updated Successfully.' : 'User Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-UserTable');
    }

    #[\Livewire\Attributes\On('edit-user')]
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $this->user_id = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->country_id = $user->country_id;
        $this->state_id = $user->state_id;
        $this->city_id = $user->city_id;
        $this->dob = $user->dob;
        $this->mobile_number = $user->mobile_number;
        $this->gender = $user->gender;
        $this->status = $user->status;

        $this->mount();
        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-user')]
    public function delete($userId)
    {
        User::find($userId)->delete();
        session()->flash('message', 'User Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-UserTable'); 
    }
}
