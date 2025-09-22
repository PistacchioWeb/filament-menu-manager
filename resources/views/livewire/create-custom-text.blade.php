<form wire:submit="save">
    <x-filament::section :heading="__('filament-menu-manager::menu-builder.custom_text')" :collapsible="true" :persist-collapsed="true" id="create-custom-text">
        {{ $this->form }}

        <x-slot:footerActions>
            <x-filament::button type="submit">
                {{ __('filament-menu-manager::menu-builder.actions.add.label') }}
            </x-filament::button>
        </x-slot:footerActions>
    </x-filament::section>
</form>
