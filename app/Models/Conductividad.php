<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Conductividad extends Model
{
    protected $table = 'conductividads';

    protected $fillable = ['valor', 'fecha_hora'];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'valor' => 'double',
    ];

    // Versión simplificada y corregida del método getDataForCharts
    public static function getDataForCharts($startDate, $endDate, $groupBy = 'hour')
    {
        try {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // Verificar si hay datos en el rango seleccionado
            $count = self::whereBetween('fecha_hora', [$start, $end])->count();

            if ($count === 0) {
                return [
                    'values' => [],
                    'labels' => [],
                    'timestamps' => [],
                    'isSingleDay' => false,
                    'stats' => [
                        'min' => 0,
                        'max' => 0,
                        'avg' => 0,
                        'count' => 0
                    ]
                ];
            }

            // Verificar si es un solo día
            $isSingleDay = $start->copy()->startOfDay()->equalTo($end->copy()->startOfDay());

            $query = self::whereBetween('fecha_hora', [$start, $end])
                ->orderBy('fecha_hora');

            $records = $query->get();

            // Inicializar arrays
            $values = [];
            $labels = [];
            $timestamps = [];

            // Lógica de agrupación simplificada
            if ($isSingleDay || $groupBy === 'hour') {
                foreach ($records as $record) {
                    $date = Carbon::parse($record->fecha_hora);
                    $values[] = (float)$record->valor;
                    $labels[] = $date->format('H:i');
                    $timestamps[] = $date->timestamp * 1000;
                }
            } elseif ($groupBy === 'day') {
                $groupedData = $records->groupBy(function ($item) {
                    return $item->fecha_hora->format('Y-m-d');
                });

                foreach ($groupedData as $day => $items) {
                    $avgValue = $items->avg('valor');
                    $date = Carbon::parse($day);
                    $values[] = (float)$avgValue;
                    $labels[] = $date->format('d/m');
                    $timestamps[] = $date->timestamp * 1000;
                }
            } else {
                $groupedData = $records->groupBy(function ($item) {
                    return $item->fecha_hora->format('Y-W');
                });

                foreach ($groupedData as $week => $items) {
                    $avgValue = $items->avg('valor');
                    $firstItem = $items->first();
                    $date = $firstItem->fecha_hora->startOfWeek();
                    $values[] = (float)$avgValue;
                    $labels[] = 'Sem ' . $date->weekOfYear . ' (' . $date->format('d/m') . ')';
                    $timestamps[] = $date->timestamp * 1000;
                }
            }

            // Calcular estadísticas
            $stats = [
                'min' => !empty($values) ? min($values) : 0,
                'max' => !empty($values) ? max($values) : 0,
                'avg' => !empty($values) ? array_sum($values) / count($values) : 0,
                'count' => count($values)
            ];

            // Asegurar que no haya valores nulos o indefinidos
            $values = array_map(function ($val) {
                return is_numeric($val) ? (float)$val : 0;
            }, $values);

            return [
                'values' => $values,
                'labels' => $labels,
                'timestamps' => $timestamps,
                'isSingleDay' => $isSingleDay,
                'stats' => $stats
            ];
        } catch (\Exception $e) {
            // Registrar el error para debugging
            Log::error('Error en getDataForCharts: ' . $e->getMessage());

            // Devolver estructura vacía en caso de error
            return [
                'values' => [],
                'labels' => [],
                'timestamps' => [],
                'isSingleDay' => false,
                'stats' => [
                    'min' => 0,
                    'max' => 0,
                    'avg' => 0,
                    'count' => 0
                ],
                'error' => $e->getMessage()
            ];
        }
    }
}
