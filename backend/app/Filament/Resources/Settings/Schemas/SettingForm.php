<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\TextInput::make('label')
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('value')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                    ])
                    ->default('text')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('group')
                    ->default('general')
                    ->maxLength(255),
            ]);
    }
}
