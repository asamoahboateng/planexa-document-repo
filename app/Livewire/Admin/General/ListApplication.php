<?php

namespace App\Livewire\Admin\General;

use App\Forms\ApplicationForm;
use App\Models\General\Application;
use App\Models\General\Meeting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;


class ListApplication extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table (Table $table): Table
    {
        return $table->recordTitle('Agendas')
            ->query(Application::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('file_number')->sortable()->searchable(),
                TextColumn::make('location.location')->sortable()->searchable(),
                TextColumn::make('meeting.name')->sortable()->searchable(),
                IconColumn::make('url')->label('view')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Application $record): string => $record->url)
                    ->openUrlInNewTab()
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([

                Action::make('viewDetail')->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->slideOver()
                    ->modalHeading('Application Details')
                    ->modalContent(fn (Application $record): View => view(
                        'backend.meetings.single_application', ['record' => $record]
                    ))->modalSubmitAction(false),

                EditAction::make()->slideOver()->form(ApplicationForm::schema()),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make()
            ])
            ->headerActions([
                CreateAction::make()->slideOver()->model(Application::class)->form(ApplicationForm::schema())
            ]);
    }

    public function render()
    {
        return view('livewire.admin.general.list-application')->extends('backend.layouts.main')->section('contents');
    }
}
