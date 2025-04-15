<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Ph;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhWidget extends ChartWidget
{
    protected static ?string $heading = 'Valores de pH a lo Largo del Tiempo';

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
            $phData = Ph::select(
                DB::raw('DATE_FORMAT(fecha_hora, "%Y-%m-%d %H:00") as hora'),
                DB::raw('AVG(valor) as ph_promedio'),
                DB::raw('MIN(valor) as ph_minimo'),
                DB::raw('MAX(valor) as ph_maximo'),
                DB::raw('COUNT(*) as total_registros')
            )
                ->where('fecha_hora', '>=', $startTime)
                ->groupBy('hora')
                ->orderBy('hora')
                ->get();

            // Preparar datos para el gráfico
            $etiquetas = $phData->pluck('hora')->toArray();
            $valores = $phData->pluck('ph_promedio')->toArray();
        } else {
            // Para múltiples días, mostrar promedio por día
            $phData = Ph::select(
                DB::raw('DATE_FORMAT(fecha_hora, "%Y-%m-%d") as dia'),
                DB::raw('AVG(valor) as ph_promedio'),
                DB::raw('MIN(valor) as ph_minimo'),
                DB::raw('MAX(valor) as ph_maximo'),
                DB::raw('COUNT(*) as total_registros')
            )
                ->where('fecha_hora', '>=', $startTime)
                ->groupBy('dia')
                ->orderBy('dia')
                ->get();

            // Preparar datos para el gráfico
            $etiquetas = $phData->pluck('dia')->toArray();
            $valores = $phData->pluck('ph_promedio')->toArray();
        }

        return [
            'labels' => $etiquetas,
            'datasets' => [
                [
                    'label' => $filter == 1
                        ? 'Promedio de pH por Hora'
                        : 'Promedio de pH por Día',
                    'data' => $valores,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.1,
                    'fill' => true
                ]
            ],
            'phData' => $phData // Añadir datos completos para una posible tabla
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
