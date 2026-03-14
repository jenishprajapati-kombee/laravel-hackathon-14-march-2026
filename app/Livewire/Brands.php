<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Brands extends Component
{
    use WithFileUploads;

    public $isModalOpen = false;
    public $brand_id, $name, $description, $logo, $status = 'Y';

    public function render()
    {
        return view('livewire.brands');
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
        $this->description = '';
        $this->logo = '';
        $this->status = 'Y';
        $this->brand_id = '';
    }

    public function store(\App\Services\BrandService $brandService)
    {
        \Keepsuit\LaravelOpenTelemetry\Facades\OpenTelemetry::tracer()->newSpan('Livewire: Store Brand')->measure(function () use ($brandService) {
            try {
                $this->validate([
                    'name' => 'required|max:191',
                    'status' => 'required|in:Y,N',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // 🔍 Structured log for Loki — searchable with: |= "validation.failed"
                \Keepsuit\LaravelOpenTelemetry\Facades\Logger::warning('validation.failed', [
                    'form'   => 'brand',
                    'action' => $this->brand_id ? 'update' : 'create',
                    'errors' => $e->errors(),
                ]);
                throw $e;
            }

            if ($this->brand_id) {
                $brandService->updateBrand($this->brand_id, [
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status
                ]);
            } else {
                $brandService->createBrand([
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status
                ]);
            }

            session()->flash('message', $this->brand_id ? 'Brand Updated Successfully.' : 'Brand Created Successfully.');
            $this->closeModal();
            
            $this->dispatch('pg:eventRefresh-BrandTable');
        });
    }

    #[\Livewire\Attributes\On('edit-brand')]
    public function edit($brandId)
    {
        \Keepsuit\LaravelOpenTelemetry\Facades\OpenTelemetry::tracer()->newSpan('Livewire: Edit Brand Modal')->measure(function () use ($brandId) {
            $brand = \App\Models\Brand::findOrFail($brandId);
            $this->brand_id = $brandId;
            $this->name = $brand->name;
            $this->description = $brand->description;
            $this->status = $brand->status;

            $this->openModal();
        });
    }

    #[\Livewire\Attributes\On('delete-brand')]
    public function delete($brandId, \App\Services\BrandService $brandService)
    {
        \Keepsuit\LaravelOpenTelemetry\Facades\OpenTelemetry::tracer()->newSpan('Livewire: Delete Brand')->measure(function () use ($brandId, $brandService) {
            $brandService->deleteBrand($brandId);
            session()->flash('message', 'Brand Deleted Successfully.');
            $this->dispatch('pg:eventRefresh-BrandTable'); 
        });
    }
}
