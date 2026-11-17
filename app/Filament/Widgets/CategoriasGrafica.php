<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Idea;
use App\Models\Categoria;

class CategoriasGrafica extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?string $heading = 'Ideas por Categoría';

    protected function getData(): array
    {
        $categorias = Categoria::withCount('ideas')->get();

        $labels = [];
        $data = [];

        foreach ($categorias as $categoria) {
            $labels[] = $categoria->nombre;
            $data[] = $categoria->ideas_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ideas',
                    'data' => $data,
                    'backgroundColor' => ['#5F9EA0', '#6495ED', '#008B8B', '#4BC0C0', '#00CED1', '#ADD8E6'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; 
    }
}