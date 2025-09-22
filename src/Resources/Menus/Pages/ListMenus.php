<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Resources\Menus\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use PistacchioWeb\FilamentMenuManager\Concerns\HasLocationAction;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;

class ListMenus extends ListRecords
{
    use HasLocationAction;

    public static function getResource(): string
    {
        return FilamentMenuManagerPlugin::get()->getResource();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            $this->getLocationAction(),
        ];
    }
}
