<?php

namespace App\Livewire\Admin\Unit;

use App\Forms\ApplicationForm;
use App\Models\General\Application;
use App\Models\General\Location;
use App\Models\General\Meeting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
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

class SingleMeeting extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public $meeting, $meetingId;

    public $modelTitle = "Single Meeting";

    public function mount($id)
    {
        $this->meeting = Meeting::find($id);
        $this->meetingId = $id;

        $this->modelTitle = "Single Location - ". $this->meeting->date;
    }

    public function table (Table $table): Table
    {
        return $table->recordTitle('Location List')
            ->query(Application::query()->where('meeting_id', $this->meetingId))
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('file_number')->sortable()->searchable(),
                TextColumn::make('location.location')->sortable()->searchable(),
                TextColumn::make('meeting.name')->sortable()->searchable(),
                IconColumn::make('url')->label('view application')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Application $record): string => $record->url)
                    ->openUrlInNewTab(),
                IconColumn::make('meeting.url')->label('view meeting')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Application $record): string => $record->meeting->url)
                    ->openUrlInNewTab()
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                Action::make('meetingDetails')
                    ->label('Meeting Details')
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->slideOver()
                    ->modalHeading('Meeting Details')
                    ->modalContent(fn (Application $record): View => view(
                        'backend.meetings.single_meeting', ['record' => $record->meeting ]
                    )),
                Action::make('viewDetail')->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->slideOver()
                    ->modalHeading('Meeting Details')
                    ->modalContent(fn (Application $record): View => view(
                        'backend.meetings.single_meeting_detailed', ['record' => $record->meeting]
                    ))->modalSubmitAction(false),
                EditAction::make()->slideOver()->form(ApplicationForm::schema()),
                DeleteAction::make()->requiresConfirmation(),
                RestoreAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()->requiresConfirmation(),
                RestoreBulkAction::make(),
            ])
            ->headerActions([]);
    }
    public function render()
    {
        return view('livewire.admin.unit.single-meeting')->extends('backend.layouts.main')->section('contents');
    }
}
