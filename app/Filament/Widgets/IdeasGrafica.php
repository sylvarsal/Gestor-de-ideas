<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Idea;
use App\Models\Categoria;

class IdeasGrafica extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Estado de las ideas';

    protected function getData(): array
    {

        $registradas = Idea::where('estado', 'registrada')->count();
        $revision = Idea::where('estado', 'en revisión')->count();
        $aprobadas = Idea::where('estado', 'aprobada')->count();
        $rechazadas = Idea::where('estado', 'rechazada')->count();
        
        return [
            'datasets' => [
                [
                    'label' => 'Estados',
                    'data' => [$registradas, $revision, $aprobadas, $rechazadas],
                    'backgroundColor' => ['#e357d5', '#c895f9', '#DDA0DD', '#7d2799'],
                ],
            ],
            'labels' => ['Registradas', 'En Revisión', 'Aprobadas', 'Rechazadas'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
