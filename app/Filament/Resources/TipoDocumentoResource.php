<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDocumentoResource\Pages;
use App\Filament\Resources\TipoDocumentoResource\RelationManagers;
use App\Models\TipoDocumento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoDocumentoResource extends Resource
{
    protected static ?string $model = TipoDocumento::class;

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descripcion_larga')
                    ->label('Descripción larga')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descripcion_corta')
                    ->label('Descripción corta')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('estado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descripcion_larga')
                    ->label('Descripción larga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion_corta')
                    ->label('Descripción corta')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('F. de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTipoDocumentos::route('/'),
        ];
    }
}
