<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
// use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use PistacchioWeb\FilamentMenuManager\Enums\LinkTarget;
use PistacchioWeb\FilamentMenuManager\Models\Menu;

class CreateCustomLink extends Component implements HasForms
{
    use InteractsWithForms;

    public Menu $menu;

    public string $title = '';

    public string $url = '';

    public string $target = LinkTarget::Self->value;

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string'],
            'url' => ['required', 'string'],
            'target' => ['required', 'string', Rule::in(LinkTarget::cases())],
        ]);

        $this->menu
            ->menuItems()
            ->create([
                'title' => $this->title,
                'url' => $this->url,
                'target' => $this->target,
                'order' => $this->menu->menuItems->max('order') + 1,
            ]);

        Notification::make()
            ->title(__('filament-menu-manager::menu-builder.notifications.created.title'))
            ->success()
            ->send();

        $this->reset('title', 'url', 'target');
        $this->dispatch('menu:created');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->label(__('filament-menu-manager::menu-builder.form.title'))
                    ->required(),
                TextInput::make('url')
                    ->label(__('filament-menu-manager::menu-builder.form.url'))
                    ->required(),
                Select::make('target')
                    ->label(__('filament-menu-manager::menu-builder.open_in.label'))
                    ->options(LinkTarget::class)
                    ->default(LinkTarget::Self),
            ]);
    }

    public function render(): View
    {
        return view('filament-menu-manager::livewire.create-custom-link');
    }
}
