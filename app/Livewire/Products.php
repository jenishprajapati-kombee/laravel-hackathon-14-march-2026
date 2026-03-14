<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Products extends Component
{
    use WithFileUploads;

    public $isModalOpen = false;
    public $product_id, $brand_id, $name, $description, $price, $stock, $sku, $image, $status = 'Y';
    public $brands = [];

    public function mount()
    {
        $this->brands = Brand::all();
    }

    public function render()
    {
        return view('livewire.products');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->brands = Brand::all();
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
        $this->product_id = '';
        $this->brand_id = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock = '';
        $this->sku = '';
        $this->image = '';
        $this->status = 'Y';
    }

    public function store()
    {
        $this->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|max:191',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'sku' => 'required|unique:products,sku,' . $this->product_id,
            'status' => 'required|in:Y,N',
        ]);

        Product::updateOrCreate(['id' => $this->product_id], [
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->product_id ? 'Product Updated Successfully.' : 'Product Created Successfully.');
        $this->closeModal();
        
        $this->dispatch('pg:eventRefresh-ProductTable');
    }

    #[\Livewire\Attributes\On('edit-product')]
    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        $this->product_id = $productId;
        $this->brand_id = $product->brand_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->sku = $product->sku;
        $this->status = $product->status;

        $this->brands = Brand::all();
        $this->openModal();
    }

    #[\Livewire\Attributes\On('delete-product')]
    public function delete($productId)
    {
        Product::find($productId)->delete();
        session()->flash('message', 'Product Deleted Successfully.');
        $this->dispatch('pg:eventRefresh-ProductTable'); 
    }
}
