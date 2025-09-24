<form wire:submit="add">
    <x-filament::section :heading="$name" :description="$description" :icon="$icon" :collapsible="$collapsible" :collapsed="$collapsed"
        :persist-collapsed="true" id="{{ $id }}-panel">
        {{ $this->form }}

        @if ($this->hasPages())
            <div class="flex items-center justify-between mt-4">
                @if ($this->hasPreviousPage())
                    <x-filament::link tag="button" wire:click="previousPage" icon="heroicon-m-chevron-left">
                        {{ __('filament-menu-manager::menu-manager.panel.pagination.previous') }}
                    </x-filament::link>
                @endif

                @if ($this->hasNextPage())
                    <x-filament::link class="ml-auto" tag="button" wire:click="nextPage" icon="heroicon-m-chevron-right"
                        iconPosition="after">
                        {{ __('filament-menu-manager::menu-manager.panel.pagination.next') }}
                    </x-filament::link>
                @endif
            </div>
        @endif

        @if ($this->items)
            <x-slot name="footer">
                <x-filament::button type="submit">
                    {{ __('filament-menu-manager::menu-manager.actions.add.label') }}
                </x-filament::button>
            </x-slot>
        @endif
    </x-filament::section>
</form>
