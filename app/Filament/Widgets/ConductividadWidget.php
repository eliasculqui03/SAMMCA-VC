<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Conductividad;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConductividadWidget extends ChartWidget
{
    protected static ?string $heading = 'Valores de Conductividad a lo Largo del Tiempo';

    // Método para definir las opciones de rango de tiempo por días
    protected function getTimeRangeOptions(): array
    {
        return [
            '1' => 'Último Día',
            '3' => 'Últimos 3 Días',
            '7' => 'Última Semana',
            '15' => 'Últimos 15 Días',
            '30' => 'Último Mes'
        ];
    }

    // Sobrescribir el método de filtro
    protected function getFilters(): ?array
    {
        return $this->getTimeRangeOptions();
    }

    protected function getData(): array
    {
        // Obtener el filtro seleccionado (por defecto 1 día si no se establece)
        $filter = $this->filter ?? 1;

        // Calcular el tiempo de inicio basado en el filtro seleccionado
        $startTime = now()->subDays($filter);

        // Lógica diferente para el último día vs. múltiples días
        if ($filter == 1) {
            // Para el último día, mostrar por horas
            $conductividadData = Conductividad::select(
                DB::raw('DATE_FORMAT(fecha_hora, "%Y-%m-%d %H:00") as hora'),
                DB::raw('AVG(valor) as conductividad_promedio'),
                DB::raw('MIN(valor) as conductividad_minima'),
                DB::raw('MAX(valor) as conductividad_maxima'),
                DB::raw('COUNT(*) as total_registros')
            )
                ->where('fecha_hora', '>=', $startTime)
                ->groupBy('hora')
                ->orderBy('hora')
                ->get();

            // Preparar datos para el gráfico
            $etiquetas = $conductividadData->pluck('hora')->toArray();
            $valores = $conductividadData->pluck('conductividad_promedio')->toArray();
        } else {
            // Para múltiples días, mostrar promedio por día
            $conductividadData = Conductividad::select(
                DB::raw('DATE_FORMAT(fecha_hora, "%Y-%m-%d") as dia'),
                DB::raw('AVG(valor) as conductividad_promedio'),
                DB::raw('MIN(valor) as conductividad_minima'),
                DB::raw('MAX(valor) as conductividad_maxima'),
                DB::raw('COUNT(*) as total_registros')
            )
                ->where('fecha_hora', '>=', $startTime)
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();

            // Preparar datos para el gráfico
            $etiquetas = $conductividadData->pluck('dia')->toArray();
            $valores = $conductividadData->pluck('conductividad_promedio')->toArray();
        }

        return [
            'labels' => $etiquetas,
            'datasets' => [
                [
                    'label' => $filter == 1
                        ? 'Promedio de Conductividad por Hora'
                        : 'Promedio de Conductividad por Día',
                    'data' => $valores,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'tension' => 0.1,
                    'fill' => true
                ]
            ],
            'conductividadData' => $conductividadData // Añadir datos completos para una posible tabla
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
