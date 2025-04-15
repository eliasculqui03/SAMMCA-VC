<?php

namespace App\Livewire;

use App\Models\Conductividad;
use App\Models\Ph;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PhEcComponente extends Component
{
    // Propiedades para filtrado
    public $selectedDate = null;
    public $availableDates = [];

    // Datos para gráficos
    public $phHourlyData = [];
    public $conductividadHourlyData = [];
    public $dailyData = [];

    // Inicialización del componente
    public function mount()
    {
        // Cargar fechas disponibles
        $this->availableDates = $this->getAvailableDates();
        // Si hay fechas, seleccionar la más reciente por defecto
        if (!empty($this->availableDates)) {
            $this->selectedDate = $this->availableDates[0];
        }

        // Cargar datos
        $this->loadData();
    }

    // Obtener fechas distintas
    protected function getAvailableDates()
    {
        $phDates = DB::table('phs')
            ->select(DB::raw('DISTINCT DATE(fecha_hora) as date'))
            ->orderBy('date', 'desc')
            ->pluck('date');

        $conductividadDates = DB::table('conductividads')
            ->select(DB::raw('DISTINCT DATE(fecha_hora) as date'))
            ->orderBy('date', 'desc')
            ->pluck('date');

        return $phDates->merge($conductividadDates)->unique()->values();
    }

    // Método para cargar datos de pH por horas
    protected function getPhHourlyData($date)
    {
        return DB::table('phs')
            ->select(
                DB::raw('HOUR(fecha_hora) as hour'),
                DB::raw('AVG(valor) as avg_valor'),
                DB::raw('MIN(valor) as min_valor'),
                DB::raw('MAX(valor) as max_valor')
            )
            ->whereDate('fecha_hora', $date)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();
    }

    // Método para cargar datos de conductividad por horas
    protected function getConductividadHourlyData($date)
    {
        return DB::table('conductividads')
            ->select(
                DB::raw('HOUR(fecha_hora) as hour'),
                DB::raw('AVG(valor) as avg_valor'),
                DB::raw('MIN(valor) as min_valor'),
                DB::raw('MAX(valor) as max_valor')
            )
            ->whereDate('fecha_hora', $date)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();
    }

    // Método para cargar datos diarios
    protected function getDailyData()
    {
        $phDaily = DB::table('phs')
            ->select(
                DB::raw('DATE(fecha_hora) as date'),
                DB::raw('AVG(valor) as avg_valor'),
                DB::raw('MIN(valor) as min_valor'),
                DB::raw('MAX(valor) as max_valor')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $conductividadDaily = DB::table('conductividads')
            ->select(
                DB::raw('DATE(fecha_hora) as date'),
                DB::raw('AVG(valor) as avg_valor'),
                DB::raw('MIN(valor) as min_valor'),
                DB::raw('MAX(valor) as max_valor')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'ph' => $phDaily,
            'conductividad' => $conductividadDaily
        ];
    }

    // Método para cargar datos cuando cambia la fecha
    public function updatedSelectedDate()
    {
        $this->loadData();
    }

    // Método principal de carga de datos
    protected function loadData()
    {
        // Cargar datos horarios si hay fecha seleccionada
        if ($this->selectedDate) {
            $this->phHourlyData = $this->getPhHourlyData($this->selectedDate);
            $this->conductividadHourlyData = $this->getConductividadHourlyData($this->selectedDate);
        }

        // Cargar datos diarios
        $this->dailyData = $this->getDailyData();
    }

    public function render()
    {
        return view('livewire.ph-ec-componente', [
            'availableDates' => $this->availableDates,
            'phHourlyData' => $this->phHourlyData,
            'conductividadHourlyData' => $this->conductividadHourlyData,
            'dailyData' => $this->dailyData
        ]);
    }
}
