<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Footer;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Header;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'ProductTable';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()->with('brand');
    }

    public function relationSearch(): array
    {
        return [
            'brand' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('brand_name', fn (Product $model) => $model->brand ? $model->brand->name : 'N/A')
            ->add('name')
            ->add('price')
            ->add('stock')
            ->add('sku')
            ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Brand', 'brand_name', 'brand_id')
                ->sortable(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Price', 'price')
                ->sortable(),
            Column::make('Stock', 'stock')
                ->sortable(),
            Column::make('SKU', 'sku')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('sku')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('edit-product', productId: $rowId);
    }

    public function actions(Product $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->class('px-3 py-2 text-sm text-white bg-blue-600 rounded cursor-pointer')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->slot('Delete')
                ->class('px-3 py-2 text-sm text-white bg-red-600 rounded cursor-pointer')
                ->dispatch('delete-product', ['productId' => $row->id]),
        ];
    }
}
