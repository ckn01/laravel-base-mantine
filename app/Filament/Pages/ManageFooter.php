<?php

namespace App\Filament\Pages;

use App\Settings\FooterSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Gate;

class ManageFooter extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = FooterSettings::class;

    // protected static string $navigationGroup = 'settings';
    protected static ?string $navigationGroup = 'Settings';

    public static function canAccess(): bool
    {
        return Gate::allows('manage Footer');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('copyright')
                    ->label('Copyright notice')
                    ->required(),
                Repeater::make('links')
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('url')
                            ->url()
                            ->required(),
                    ]),
            ]);
    }
}
