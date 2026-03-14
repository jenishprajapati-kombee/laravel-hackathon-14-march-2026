<?php

namespace App\Livewire;

use App\Models\State;
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

final class StateTable extends PowerGridComponent
{
    public string $tableName = 'StateTable';

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
        return State::query()->with('country');
    }

    public function relationSearch(): array
    {
        return [
            'country' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('country_name', fn (State $model) => $model->country ? $model->country->name : 'N/A')
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Country', 'country_name', 'country_id')
                ->sortable(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('edit-state', stateId: $rowId);
    }

    public function actions(State $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->class('px-3 py-2 text-sm text-white bg-blue-600 rounded cursor-pointer')
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->slot('Delete')
                ->class('px-3 py-2 text-sm text-white bg-red-600 rounded cursor-pointer')
                ->dispatch('delete-state', ['stateId' => $row->id]),
        ];
    }
}
