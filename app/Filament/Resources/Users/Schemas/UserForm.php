<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserTypeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->maxLength(255)
                    ->unique('users', 'email', ignoreRecord: true)
                    ->required(),
                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->rules(['phone'])
                    ->maxLength(20)
                    ->unique('users', 'phone', ignoreRecord: true)
                    ->nullable(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->translateLabel()
                    ->maxLength(255)
                    ->confirmed()
                    ->required(fn ($component, $get, $model, $record, $set, $state) => $record === null)
                    ->dehydrateStateUsing(function ($state, $operation) {
                        if (! filled($state)) {
                            return null;
                        } elseif ($operation === 'create') {
                            return $state;
                        } elseif ($operation === 'edit') {
                            return bcrypt($state);
                        }

                        return null;
                    })
                    ->dehydrated(fn ($state) => filled($state)),
                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->translateLabel()
                    ->dehydrated(false)
                    ->required(fn ($component, $get, $model, $record, $set, $state) => $record === null)
                    ->same('password')
                    ->maxLength(255),
                Select::make('type')
                    ->options(UserTypeEnum::class)
                    ->required(),
            ]);
    }
}
