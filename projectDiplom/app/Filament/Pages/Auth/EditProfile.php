<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->required()
                    ->label('Имя')
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->required() 
                    ->label('Фамилия')
                    ->maxLength(255),
                TextInput::make('middle_name')
                    ->label('Отчество')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->required()
                    ->label('Телефон')
                    ->tel()
                    ->maxLength(255),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}