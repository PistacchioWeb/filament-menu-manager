<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use PistacchioWeb\FilamentMenuManager\Models\Menu;

class CreateCustomText extends Component implements HasForms
{
    use InteractsWithForms;

    public Menu $menu;

    public string $title = '';

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string'],
        ]);

        $this->menu
            ->menuItems()
            ->create([
                'title' => $this->title,
                'order' => $this->menu->menuItems->max('order') + 1,
            ]);

        Notification::make()
            ->title(__('filament-menu-manager::menu-builder.notifications.created.title'))
            ->success()
            ->send();

        $this->reset('title');
        $this->dispatch('menu:created');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('filament-menu-manager::menu-builder.form.title'))
                    ->required(),
            ]);
    }

    public function render(): View
    {
        return view('filament-menu-manager::livewire.create-custom-text');
    }
}
