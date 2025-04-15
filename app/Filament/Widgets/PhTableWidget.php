<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Ph;
use Carbon\Carbon;

class PhTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Detalle de Valores de pH';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ph::query()
                    ->where('fecha_hora', '>=', now()->subDays(1))
                    ->orderBy('fecha_hora', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('fecha_hora')
                    ->label('Fecha y Hora')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('valor')
                    ->label('Valor de pH')
                    ->color(function ($record) {
                        return match (true) {
                            $record->valor < 7 => 'danger',
                            $record->valor >= 7 && $record->valor <= 7.5 => 'success',
                            $record->valor > 7.5 => 'info',
                            default => 'primary',
                        };
                    }),
            ])
            ->defaultSort('fecha_hora', 'desc')
            ->paginated([10, 25, 50]);
    }
}
