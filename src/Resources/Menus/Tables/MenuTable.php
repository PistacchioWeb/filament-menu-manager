<?php

namespace PistacchioWeb\FilamentMenuManager\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;

class MenuTable
{
    public static function configure(Table $table): Table
    {

        $locations = FilamentMenuManagerPlugin::get()->getLocations();

        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('menuItems'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('filament-menu-manager::menu-builder.resource.name.label')),
                TextColumn::make('locations.location')
                    ->label(__('filament-menu-manager::menu-builder.resource.locations.label'))
                    ->default(__('filament-menu-manager::menu-builder.resource.locations.empty'))
                    ->color(fn (string $state) => array_key_exists($state, $locations) ? 'primary' : 'gray')
                    ->formatStateUsing(fn (string $state) => $locations[$state] ?? $state)
                    ->limitList(2)
                    ->sortable()
                    ->badge(),
                TextColumn::make('menu_items_count')
                    ->label(__('filament-menu-manager::menu-builder.resource.items.label'))
                    ->icon('heroicon-o-link')
                    ->numeric()
                    ->default(0)
                    ->sortable(),
                IconColumn::make('is_visible')
                    ->label(__('filament-menu-manager::menu-builder.resource.is_visible.label'))
                    ->sortable()
                    ->boolean(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }
}
