<?php

declare(strict_types=1);

namespace PistacchioWeb\FilamentMenuManager\MenuPanel;

use Closure;
use Illuminate\Database\Eloquent\Model;

class ModelMenuPanel extends AbstractMenuPanel
{
    /**
     * @var \Illuminate\Database\Eloquent\Model&\PistacchioWeb\FilamentMenuManager\Contracts\MenuPanelable
     */
    protected Model $model;

    protected Closure $urlUsing;

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model&\PistacchioWeb\FilamentMenuManager\Contracts\MenuPanelable>  $model
     */
    public function model(string $model): static
    {
        $this->model = new $model;

        return $this;
    }

    public function getName(): string
    {
        return $this->model->getMenuPanelName();
    }

    public function getItems(): array
    {
        return ($this->model->getMenuPanelModifyQueryUsing())($this->model->newQuery())
            ->get()
            ->map(fn (Model $model) => [
                'title' => $model->{$this->model->getMenuPanelTitleColumn()},
                'linkable_type' => $model->getMorphClass(),
                'linkable_id' => $model->getKey(),
            ])
            ->all();
    }
}
