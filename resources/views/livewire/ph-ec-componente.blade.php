<div class="bg-gray-100 min-h-screen py-10">
    @vite('resources/css/app.css')
    <div class="container mx-auto px-4">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white p-4">
                <h1 class="text-2xl font-bold">Análisis de Calidad del Agua</h1>
            </div>

            <div class="p-6">
                <!-- Selector de Fecha -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date-select">
                        Seleccionar Fecha:
                    </label>
                    <div class="relative">
                        <select wire:model.live="selectedDate" id="date-select"
                            class="block appearance-none w-full bg-white border border-gray-300 text-gray-900 py-2 px-3 pr-8 rounded-lg focus:outline-none focus:border-blue-500">
                            @foreach ($availableDates as $date)
                                <option value="{{ $date }}">{{ $date }}</option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Gráficos de Datos Horarios -->
                @if ($selectedDate)
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Gráfico de pH -->
                        <div class="bg-white shadow-md rounded-lg p-4 border">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800">pH por Horas ({{ $selectedDate }})</h2>
                            <div wire:ignore class="h-64">
                                <canvas id="phHourlyChart"></canvas>
                            </div>
                        </div>

                        <!-- Gráfico de Conductividad -->
                        <div class="bg-white shadow-md rounded-lg p-4 border">
                            <h2 class="text-xl font-semibold mb-4 text-gray-800">Conductividad por Horas
                                ({{ $selectedDate }})</h2>
                            <div wire:ignore class="h-64">
                                <canvas id="conductividadHourlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Gráficos de Datos Diarios -->
                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    <!-- Gráfico Diario de pH -->
                    <div class="bg-white shadow-md rounded-lg p-4 border">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">pH - Resumen Diario</h2>
                        <div wire:ignore class="h-64">
                            <canvas id="phDailyChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico Diario de Conductividad -->
                    <div class="bg-white shadow-md rounded-lg p-4 border">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Conductividad - Resumen Diario</h2>
                        <div wire:ignore class="h-64">
                            <canvas id="conductividadDailyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:load', () => {
                // Función para crear configuración de gráfico base
                const createChartConfig = (labels, datasets, yAxisTitle, beginAtZero = false) => ({
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Valor Promedio',
                                data: datasets.map(d => d.avg_valor),
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 2
                            },
                            {
                                label: 'Valor Mínimo',
                                data: datasets.map(d => d.min_valor),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 2
                            },
                            {
                                label: 'Valor Máximo',
                                data: datasets.map(d => d.max_valor),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: beginAtZero,
                                title: {
                                    display: true,
                                    text: yAxisTitle
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hora / Día'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });

                // Gráfico de pH por Horas
                @if (!empty($phHourlyData))
                    const phHourlyCtx = document.getElementById('phHourlyChart');
                    new Chart(phHourlyCtx, createChartConfig(
                        @json($phHourlyData->pluck('hour')),
                        @json($phHourlyData),
                        'Valor de pH'
                    ));
                @endif

                // Gráfico de Conductividad por Horas
                @if (!empty($conductividadHourlyData))
                    const conductividadHourlyCtx = document.getElementById('conductividadHourlyChart');
                    new Chart(conductividadHourlyCtx, createChartConfig(
                        @json($conductividadHourlyData->pluck('hour')),
                        @json($conductividadHourlyData),
                        'Conductividad (µS/cm)'
                    ));
                @endif

                // Gráfico Diario de pH
                const phDailyCtx = document.getElementById('phDailyChart');
                new Chart(phDailyCtx, createChartConfig(
                    @json($dailyData['ph']->pluck('date')),
                    @json($dailyData['ph']),
                    'Valor de pH'
                ));

                // Gráfico Diario de Conductividad
                const conductividadDailyCtx = document.getElementById('conductividadDailyChart');
                new Chart(conductividadDailyCtx, createChartConfig(
                    @json($dailyData['conductividad']->pluck('date')),
                    @json($dailyData['conductividad']),
                    'Conductividad (µS/cm)'
                ));
            });
        </script>
    @endpush
</div>
