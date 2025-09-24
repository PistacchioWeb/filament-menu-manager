<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Concerns;

use Filament\Actions\Action;
use Filament\Support\Enums\Size as ActionSize;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;
use PistacchioWeb\FilamentMenuManager\Services\MenuItemService;

trait ManagesMenuItemHierarchy
{
    protected ?MenuItemService $menuItemService = null;

    public function indent(int $itemId): void
    {
        $this->getMenuItemService()->indent($itemId);
    }

    public function unindent(int $itemId): void
    {
        $this->getMenuItemService()->unindent($itemId);
    }

    public function canIndent(int $itemId): bool
    {
        return $this->getMenuItemService()->canIndent($itemId);
    }

    public function canUnindent(int $itemId): bool
    {
        return $this->getMenuItemService()->canUnindent($itemId);
    }

    public function indentAction(): Action
    {
        return Action::make('indent')
            ->label(__('filament-menu-manager::menu-manager.actions.indent'))
            ->icon('heroicon-o-arrow-right')
            ->color('gray')
            ->iconButton()
            ->size(ActionSize::Small)
            ->action(fn (array $arguments) => $this->indent($arguments['id']))
            ->visible(fn (array $arguments): bool => $this->isIndentActionVisible($arguments['id']));
    }

    public function unindentAction(): Action
    {
        return Action::make('unindent')
            ->label(__('filament-menu-manager::menu-manager.actions.unindent'))
            ->icon('heroicon-o-arrow-left')
            ->color('gray')
            ->iconButton()
            ->size(ActionSize::Small)
            ->action(fn (array $arguments) => $this->unindent($arguments['id']))
            ->visible(fn (array $arguments): bool => $this->isUnindentActionVisible($arguments['id']));
    }

    protected function isIndentActionVisible(int $itemId): bool
    {
        return FilamentMenuManagerPlugin::get()->isIndentActionsEnabled() &&
               $this->canIndent($itemId);
    }

    protected function isUnindentActionVisible(int $itemId): bool
    {
        return FilamentMenuManagerPlugin::get()->isIndentActionsEnabled() &&
               $this->canUnindent($itemId);
    }

    protected function getMenuItemService(): MenuItemService
    {
        if ($this->menuItemService === null) {
            $this->menuItemService = new MenuItemService;
        }

        return $this->menuItemService;
    }
}
