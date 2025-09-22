<?php

namespace PistacchioWeb\FilamentMenuManager;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use PistacchioWeb\FilamentMenuManager\Livewire\CreateCustomLink;
use PistacchioWeb\FilamentMenuManager\Livewire\CreateCustomText;
use PistacchioWeb\FilamentMenuManager\Livewire\MenuItems;
use PistacchioWeb\FilamentMenuManager\Livewire\MenuPanel;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMenuManagerPackageServiceProvider extends PackageServiceProvider
{
    public static string $packageName = 'filament-menu-manager';

    public static string $viewNamespace = 'filament-menu-manager';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$packageName)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),
        );

        Livewire::component('menu-builder-items', MenuItems::class);
        Livewire::component('menu-builder-panel', MenuPanel::class);
        Livewire::component('create-custom-link', CreateCustomLink::class);
        Livewire::component('create-custom-text', CreateCustomText::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'pistacchioweb/filament-menu-manager';
    }

    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-menu-manager', __DIR__.'/../resources/dist/filament-menu-manager.js'),
            Css::make('filament-menu-manager-styles', __DIR__.'/../resources/dist/filament-menu-manager.css'),
        ];
    }

    protected function getMigrations(): array
    {
        return [
            'create_menus_table',
        ];
    }

    public function packageRegistered(): void {}
}
