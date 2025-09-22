<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PistacchioWeb\FilamentMenuManager\FilamentMenuManagerPlugin;

/**
 * @property int $id
 * @property int $menu_id
 * @property string $location
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \PistacchioWeb\FilamentMenuManager\Models\Menu $menu
 */
class MenuLocation extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return config('filament-menu-manager.tables.menu_locations', parent::getTable());
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(FilamentMenuManagerPlugin::get()->getMenuModel());
    }
}
