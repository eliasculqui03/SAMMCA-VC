<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MiembroResource\Pages;
use App\Filament\Resources\MiembroResource\RelationManagers;
use App\Models\Miembro;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MiembroResource extends Resource
{
    protected static ?string $model = Miembro::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make()
                    ->schema([

                        Section::make('Datos generales')
                            ->schema([
                                Forms\Components\TextInput::make('nombre_miembro')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Select::make('tipo_documento_id')
                                    ->relationship('tipoDocumento', 'descripcion_corta')
                                    ->required(),
                                Forms\Components\TextInput::make('numero_documento')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ])->columnSpan(1)
                            ->columns(2),

                        Section::make('Información')
                            ->schema([
                                Forms\Components\TextInput::make('edad')
                                    ->numeric()
                                    ->default(null),
                                Forms\Components\TextInput::make('telefono')
                                    ->tel()
                                    ->maxLength(255)
                                    ->default(null),
                                Forms\Components\TextInput::make('correo')
                                    ->email()
                                    ->default(null),
                                Forms\Components\TextInput::make('direccion')
                                    ->maxLength(255)
                                    ->default(null),

                            ])
                            ->columns(2)
                            ->columnSpan(1),

                    ])->columns(2),

                Section::make('Detalles de cargo')
                    ->schema([
                        Forms\Components\Select::make('cargo_id')
                            ->relationship('cargo', 'nombre_cargo')
                            ->required(),
                        Forms\Components\DatePicker::make('inicio_periodo')
                            ->required(),
                        Forms\Components\DatePicker::make('final_periodo')
                            ->required(),
                        Forms\Components\RichEditor::make('informacion')
                            ->label('Información')
                            ->fileAttachmentsDirectory('informacion')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Forms\Components\Toggle::make('estado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_miembro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoDocumento.descripcion_corta')
                    ->label('T. de documento'),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cargo.nombre_cargo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inicio_periodo')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_periodo')
                    ->date()
                    ->sortable(),
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
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMiembros::route('/'),
            'create' => Pages\CreateMiembro::route('/create'),
            'edit' => Pages\EditMiembro::route('/{record}/edit'),
        ];
    }
}
