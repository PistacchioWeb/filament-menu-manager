<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Resources\Menus;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;
use PistacchioWeb\FilamentMenuManager\Resources\Menus\Pages\EditMenu;
use PistacchioWeb\FilamentMenuManager\Resources\Menus\Pages\ListMenus;
use PistacchioWeb\FilamentMenuManager\Resources\Menus\Schemas\MenuForm;
use PistacchioWeb\FilamentMenuManager\Resources\Menus\Tables\MenuTable;

class MenuResource extends Resource
{
    public static function getModel(): string
    {
        return FilamentMenuManagerPlugin::get()->getMenuModel();
    }

    public static function getNavigationLabel(): string
    {
        return FilamentMenuManagerPlugin::get()->getNavigationLabel() ?? Str::title(static::getPluralModelLabel()) ?? Str::title(static::getModelLabel());
    }

    public static function getNavigationIcon(): string
    {
        return FilamentMenuManagerPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationSort(): ?int
    {
        return FilamentMenuManagerPlugin::get()->getNavigationSort();
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentMenuManagerPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationBadge(): ?string
    {
        return FilamentMenuManagerPlugin::get()->getNavigationCountBadge() ? number_format(static::getModel()::count()) : null;
    }

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuTable::configure($table);
    }

    public static function getRelationManagersContentComponent(){

    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
