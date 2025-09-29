@php use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin; @endphp
<x-filament-panels::page @class([
    'fi-resource-edit-record-page',
    'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    'fi-resource-record-' . $record->getKey(),
])>
    {{$this->content}}

    <div class="grid grid-cols-12 gap-4" wire:ignore>
        <div class="flex flex-col col-span-12 gap-4 sm:col-span-4">
            @foreach (FilamentMenuManagerPlugin::get()->getMenuPanels() as $menuPanel)
                <livewire:menu-builder-panel :menu="$record" :menuPanel="$menuPanel" />
            @endforeach

            @if (FilamentMenuManagerPlugin::get()->isShowCustomLinkPanel())
                <livewire:create-custom-link :menu="$record" />
            @endif

            @if (FilamentMenuManagerPlugin::get()->isShowCustomTextPanel())
                <livewire:create-custom-text :menu="$record" />
            @endif
        </div>
        <div class="col-span-12 sm:col-span-8">
            <x-filament::section>
                <livewire:menu-builder-items :menu="$record" />
            </x-filament::section>
        </div>
    </div>

</x-filament-panels::page>
