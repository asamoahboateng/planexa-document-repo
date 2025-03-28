<?php

namespace App\Forms;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
final class LocationForm
{

    public  static function schema(): array
    {
        return [
            TextInput::make('location')->required(),
            TextInput::make('postal_code'),
            TextInput::make('lat')->numeric()->inputMode('decimal'),
            TextInput::make('long')->numeric()->inputMode('decimal'),
            Select::make('province')->options([
                'ON' => 'ON',
                'AB' => 'AB',
                'NS' => 'NS'
            ])->required(),
            TextInput::make('ward')->required(),
        ];
    }
}
//'location',
//        'province',
//        'ward',
//        'user_id',
//        'lat',
//        'long',
//        'postal_code',
