<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Resources\Menus\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use PistacchioWeb\FilamentMenuManager\Concerns\HasLocationAction;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;

class EditMenu extends EditRecord
{
    use HasLocationAction;

    protected static string $view = 'filament-menu-manager::edit-record';

    public static function getResource(): string
    {
        return FilamentMenuManagerPlugin::get()->getResource();
    }

    // public function form(Schema $schema): Schema
    // {
    //     return static::getResource()::form($schema);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            $this->getLocationAction(),
        ];
    }
}
