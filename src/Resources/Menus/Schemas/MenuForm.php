<?php

namespace PistacchioWeb\FilamentMenuManager\Resources\Menus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament-menu-manager::menu-manager.resource.name.label'))
                            ->required()
                            ->columnSpan(3),

                        ToggleButtons::make('is_visible')
                            ->grouped()
                            ->options([
                                true => __('filament-menu-manager::menu-manager.resource.is_visible.visible'),
                                false => __('filament-menu-manager::menu-manager.resource.is_visible.hidden'),
                            ])
                            ->colors([
                                true => 'primary',
                                false => 'danger',
                            ])
                            ->required()
                            ->label(__('filament-menu-manager::menu-manager.resource.is_visible.label'))
                            ->default(true),
                    ]),

                Group::make()
                    ->visible(fn (Component $component) => $component->evaluate(FilamentMenuManagerPlugin::get()->getMenuFields()) !== [])
                    ->schema(FilamentMenuManagerPlugin::get()->getMenuFields()),

            ])->columns(1);
    }
}
