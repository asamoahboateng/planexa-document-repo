<?php

namespace App\Livewire\Admin\General;

use App\Forms\LocationForm;
use App\Models\General\Location;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use App\Models\General\Meeting;


class ListLocations extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table->recordTitle('Locations')
            ->query(Location::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('location')->sortable()->searchable(),
                TextColumn::make('postal_code')->sortable()->searchable(),
                TextColumn::make('province')->sortable()->searchable(),
                TextColumn::make('lat')->sortable()->searchable(),
                TextColumn::make('long')->sortable()->searchable(),
                TextColumn::make('applications_count')->label('Applications')->counts('applications'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make()->slideOver()->model(Location::class)->form(LocationForm::schema()),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make()
            ])
            ->headerActions([
                CreateAction::make()->slideOver()->model(Location::class)->form(LocationForm::schema())
            ]);
    }
    public function render()
    {
        return view('livewire.admin.general.list-locations')->extends('backend.layouts.main')->section('contents');
    }
}
