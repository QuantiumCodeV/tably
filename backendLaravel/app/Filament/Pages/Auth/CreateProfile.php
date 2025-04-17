<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register;

class CreateProfile extends Register
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('last_name') 
                    ->label('Фамилия')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('middle_name')
                    ->label('Отчество')
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique('users')
                    ->maxLength(255),
                    
                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->required() 
                    ->unique('users')
                    ->maxLength(255),
                    
                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('passwordConfirmation'),
                    
                TextInput::make('passwordConfirmation')
                    ->label('Подтверждение пароля')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->dehydrated(false),
            ]);
    }
}
