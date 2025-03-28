<?php

namespace App\Livewire\Admin\General;

use App\Models\General\Application;
use App\Models\General\Meeting;
use App\Models\General\MeetingVideo;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\TextEntry as InfoTextEntry;
use Filament\Infolists\Components\Actions\Action as InfoAction;
use Illuminate\Contracts\View\View;
class ListMeetingVideos extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public $modelTitle = "Meeting Videos";
    public function table(Table $table): Table
    {
        return $table->recordTitle('Meeting Videos')
            ->query(MeetingVideo::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('meeting.name')->sortable()->searchable()
                    ->label('Meeting Name')
                    ->action(
                        Action::make('meetingDetails')
                            ->label('Meeting Details')
                            ->icon('heroicon-o-eye')
                            ->slideOver()
                            ->modalHeading('Meeting Details')
                            ->modalContent(fn (MeetingVideo $record): View => view(
                                'backend.meetings.single_meeting', ['record' => $record->meeting ]
                            ))
                        ->modalSubmitAction(false)
                    ),
                TextColumn::make('video_title')->sortable()->searchable()
                    ->label('Title')
                    ->action(
                    Action::make('viewDetails')
                        ->label('View Details')
                        ->slideOver()
                        ->modalHeading('Video Details')
                        ->modalContent(fn (MeetingVideo $record): View => view(
                            'backend.meetings.single_video', ['record' => $record]
                        ))->modalSubmitAction(false)
                    ),
//                IconColumn::make('url')->label('view')
//                    ->icon('heroicon-o-arrow-top-right-on-square')
//                    ->url(fn (MeetingVideo $record): string => $record->url)
//                    ->openUrlInNewTab(),
//                IconColumn::make('video_transcript')->label('transcript')
//                    ->icon('heroicon-o-arrow-top-right-on-square')
//                    ->url(fn (MeetingVideo $record): string => 'http://192.3.155.50/prg/'. $record->video_transcript)
//                    ->openUrlInNewTab()
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
//                DeleteAction::make(),
                Action::make('viewDetails')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->slideOver()
                    ->modalHeading('Video Details')
                    ->modalContent(fn (MeetingVideo $record): View => view(
                        'backend.meetings.single_video', ['record' => $record]
                    ))->modalSubmitAction(false),
                Action::make('meetingDetails')
                    ->label('Meeting Details')
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->slideOver()
                    ->modalHeading('Meeting Details')
                    ->modalContent(fn (MeetingVideo $record): View => view(
                        'backend.meetings.single_meeting', ['record' => $record->meeting ]
                    ))
                    ->modalSubmitAction(false),
                EditAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
//                DeleteBulkAction::make(),
//                RestoreBulkAction::make()
            ])
            ->headerActions([
//                CreateAction::make()->slideOver()->model(MeetingVideo::class)->form([
//                    TextInput::make('video_url')->required(),
//                ])
            ]);
    }

    public function render()
    {
        return view('livewire.admin.general.list-meeting-videos')->extends('backend.layouts.main')->section('contents');
    }
}
