<?php

namespace App\Livewire\Admin\General;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use App\Models\General\Meeting;
use Livewire\Component;

class ListMeetings extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table (Table $table): Table
    {
        return $table->recordTitle('Meetings')
            ->query(Meeting::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('date')->sortable()->searchable(),
                TextColumn::make('governing_committee')->sortable()->searchable(),
                TextColumn::make('district')->sortable()->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make()
            ])
            ->headerActions([
                CreateAction::make()->slideOver()->model(Meeting::class)->form([
                    DatePicker::make('date')->required(),
                    TextInput::make('governing_committee')->required(),
                    TextInput::make('district')->required(),
                ])
            ]);
    }

    public function render()
    {
        return view('livewire.admin.general.list-meetings')->extends('backend.layouts.main')->section('contents');
    }
}
