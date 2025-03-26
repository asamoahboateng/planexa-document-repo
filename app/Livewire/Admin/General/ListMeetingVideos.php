<?php

namespace App\Livewire\Admin\General;

use App\Models\General\MeetingVideo;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
class ListMeetingVideos extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table->recordTitle('Meeting Videos')
            ->query(MeetingVideo::query())
            ->columns([
                TextColumn::make('no')->rowIndex(),
                TextColumn::make('meeting')->sortable()->searchable(),
                TextColumn::make('video_url')->sortable()->searchable(),
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
                CreateAction::make()->slideOver()->model(MeetingVideo::class)->form([
                    TextInput::make('video_url')->required(),
                ])
            ]);
    }

    public function render()
    {
        return view('livewire.admin.general.list-meeting-videos')->extends('backend.layouts.main')->section('contents');
    }
}
