<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentIcon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Установка русской локализации для Filament
        App::setLocale('ru');
        
        // Настройка лейблов по умолчанию для компонентов форм
        TextInput::configureUsing(function (TextInput $input) {
            $input->translateLabel();
        });
        
        Select::configureUsing(function (Select $select) {
            $select->translateLabel();
        });
        
        DateTimePicker::configureUsing(function (DateTimePicker $picker) {
            $picker->translateLabel();
            $picker->displayFormat('d.m.Y H:i');
        });
        
        FileUpload::configureUsing(function (FileUpload $upload) {
            $upload->translateLabel();
        });
        
        Textarea::configureUsing(function (Textarea $textarea) {
            $textarea->translateLabel();
        });
        
        Toggle::configureUsing(function (Toggle $toggle) {
            $toggle->translateLabel();
        });
        
        // Настройка колонок таблиц
        TextColumn::configureUsing(function (TextColumn $column) {
            $column->translateLabel();
        });
        
        ImageColumn::configureUsing(function (ImageColumn $column) {
            $column->translateLabel();
        });
        
        ToggleColumn::configureUsing(function (ToggleColumn $column) {
            $column->translateLabel();
        });
    }
}
