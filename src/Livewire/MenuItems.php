<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Size as ActionSize;
use Filament\Support\Enums\Width as MaxWidth;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use PistacchioWeb\FilamentMenuManager\Concerns\ManagesMenuItemHierarchy;
use PistacchioWeb\FilamentMenuManager\Enums\LinkTarget;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;
use PistacchioWeb\FilamentMenuManager\Models\Menu;

class MenuItems extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
    use ManagesMenuItemHierarchy;

    public Menu $menu;

    #[Computed]
    #[On('menu:created')]
    public function menuItems(): Collection
    {
        return $this->menu->menuItems;
    }

    public function reorder(array $order, ?string $parentId = null): void
    {
        $this->getMenuItemService()->updateOrder($order, $parentId);
    }

    public function reorderAction(): Action
    {
        return Action::make('reorder')
            ->label(__('filament-forms::components.builder.actions.reorder.label'))
            ->icon(FilamentIcon::resolve('forms::components.builder.actions.reorder') ?? 'heroicon-m-arrows-up-down')
            ->color('gray')
            ->iconButton()
            ->extraAttributes(['data-sortable-handle' => true, 'class' => 'cursor-move'])
            ->livewireClickHandlerEnabled(false)
            ->size(ActionSize::Small);
    }

    public function indent(int $itemId): void
    {
        $item = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()->find($itemId);

        if (! $item) {
            return;
        }

        $previousSibling = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()
            ->where('menu_id', $item->menu_id)
            ->where('parent_id', $item->parent_id)
            ->where('order', '<', $item->order)
            ->orderByDesc('order')
            ->first();

        if (! $previousSibling) {
            return;
        }

        $maxOrder = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()
            ->where('parent_id', $previousSibling->id)
            ->max('order') ?? 0;

        $item->update([
            'parent_id' => $previousSibling->id,
            'order' => $maxOrder + 1,
        ]);

        $this->reorderSiblings($item->getOriginal('parent_id'));
    }

    public function unindent(int $itemId): void
    {
        $item = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()->find($itemId);

        if (! $item || ! $item->parent_id) {
            return;
        }

        $parent = $item->parent;
        if (! $parent) {
            return;
        }

        $maxOrder = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()
            ->where('menu_id', $item->menu_id)
            ->where('parent_id', $parent->parent_id)
            ->max('order') ?? 0;

        $oldParentId = $item->parent_id;

        $item->update([
            'parent_id' => $parent->parent_id,
            'order' => $maxOrder + 1,
        ]);

        $this->reorderSiblings($oldParentId);
    }

    private function reorderSiblings(?int $parentId): void
    {
        $siblings = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()
            ->where('parent_id', $parentId)
            ->orderBy('order')
            ->get();

        $siblings->each(function ($sibling, $index) {
            $sibling->update(['order' => $index + 1]);
        });
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
            ->visible(
                fn (array $arguments): bool => FilamentMenuManagerPlugin::get()->isIndentActionsEnabled() &&
                    $this->canIndent($arguments['id']),
            );
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
            ->visible(
                fn (array $arguments): bool => FilamentMenuManagerPlugin::get()->isIndentActionsEnabled() &&
                    $this->canUnindent($arguments['id']),
            );
    }

    public function canIndent(int $itemId): bool
    {
        $item = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()->find($itemId);

        if (! $item) {
            return false;
        }

        $previousSibling = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()
            ->where('menu_id', $item->menu_id)
            ->where('parent_id', $item->parent_id)
            ->where('order', '<', $item->order)
            ->orderByDesc('order')
            ->first();

        return $previousSibling !== null;
    }

    public function canUnindent(int $itemId): bool
    {
        $item = FilamentMenuManagerPlugin::get()->getMenuItemModel()::query()->find($itemId);

        return $item && $item->parent_id !== null;
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label(__('filament-actions::edit.single.label'))
            ->iconButton()
            ->size(ActionSize::Small)
            ->modalHeading(fn (array $arguments): string => __('filament-actions::edit.single.modal.heading', ['label' => $arguments['title']]))
            ->icon('heroicon-m-pencil-square')
            ->fillForm(fn (array $arguments): array => $this->getMenuItemService()->findByIdWithRelations($arguments['id'])->toArray())
            ->schema($this->getEditFormSchema())
            ->action(function (array $data, array $arguments) {
                // dd($this->getMenuItemService());
                $this->getMenuItemService()->update($arguments['id'], $data);
            })
            ->modalWidth(MaxWidth::Medium)
            ->slideOver();
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('filament-actions::delete.single.label'))
            ->color('danger')
            ->groupedIcon(FilamentIcon::resolve('actions::delete-action.grouped') ?? 'heroicon-m-trash')
            ->icon('heroicon-s-trash')
            ->iconButton()
            ->size(ActionSize::Small)
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments): string => __('filament-actions::delete.single.modal.heading', ['label' => $arguments['title']]))
            ->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'))
            ->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-trash')
            ->action(function (array $arguments): void {
                $this->getMenuItemService()->delete($arguments['id']);
            });
    }

    public function render(): View
    {
        return view('filament-menu-manager::livewire.menu-items');
    }

    protected function getEditFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('filament-menu-manager::menu-manager.form.title'))
                ->required(),
            TextInput::make('url')
                ->hidden(fn (?string $state, Get $get): bool => blank($state) || filled($get('linkable_type')))
                ->label(__('filament-menu-manager::menu-manager.form.url'))
                ->required(),
            Group::make([
                TextInput::make('linkable_type')
                    ->label(__('filament-menu-manager::menu-manager.form.linkable_type'))
                    ->hidden(fn (?string $state): bool => blank($state))->disabled()->readOnly(),
                TextInput::make('linkable_id')
                    ->label(__('filament-menu-manager::menu-manager.form.linkable_id'))
                    ->hidden(fn (?string $state): bool => blank($state))->disabled()->readOnly(),
            ])->columns(2),
            Select::make('target')
                ->label(__('filament-menu-manager::menu-manager.open_in.label'))
                ->options(LinkTarget::class)
                ->default(LinkTarget::Self),
            Group::make()
                ->visible(fn (\Filament\Schemas\Components\Group $component) => $component->evaluate(FilamentMenuManagerPlugin::get()->getMenuItemFields()) !== [])
                ->schema(FilamentMenuManagerPlugin::get()->getMenuItemFields()),
        ];
    }
}
