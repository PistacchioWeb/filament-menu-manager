<div>
    @if ($this->menuItems->isNotEmpty())
        <ul x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-menu-manager', 'pistacchioweb/filament-menu-manager') }}"
            x-data="MenuBuilder({ parentId: 0 })" class="space-y-2">
            @foreach ($this->menuItems as $menuItem)
                <x-filament-menu-manager::menu-item :item="$menuItem" />
            @endforeach
        </ul>
    @else
        <x-filament-menu-manager::tables.empty-state icon="heroicon-o-document" :heading="trans('filament-menu-manager::menu-manager.items.empty.heading')" />
    @endif

    <x-filament-actions::modals />
</div>
