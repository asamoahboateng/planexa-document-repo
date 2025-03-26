<?php

namespace App\Forms;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
final class ApplicationForm
{

    public  static function schema(): array
    {
        return [
            Forms\Components\Select::make('location_id')->relationship('location', 'location')->preload()->required(),
            Forms\Components\Select::make('meeting_id')->relationship('meeting', 'name')->preload()->required(),
            TextInput::make('title')->required(),
            TextInput::make('file_number')->required(),
            TextInput::make('application_number')->required(),
            TextInput::make('type')->nullable(),
            TextInput::make('url')->required(),
            TextInput::make('status')->nullable(),
            Textarea::make('related_application')->nullable(),
            Textarea::make('description')->required(),
        ];
    }
}
