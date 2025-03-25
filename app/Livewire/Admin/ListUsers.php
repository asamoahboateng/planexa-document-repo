<?php

namespace App\Livewire\Admin;

use App\Models\User as UserModel;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ListUsers extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms, AuthorizesRequests;



    public function table(Table $table): Table
    {
        return $table->recordTitleAttribute('Users')
            ->query(UserModel::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable(),
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
                CreateAction::make()->slideOver()->model(UserModel::class)->form([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->required(),
                    TextInput::make('password')->required(),
                    Select::make('role')->options(['admin', 'customer'])
                ])
            ]);
    }

    public function render()
    {
//        return 'gee';
        return view('livewire.admin.list-users')->extends('backend.layouts.main')->section('contents');
    }
}
