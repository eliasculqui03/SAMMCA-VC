<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $label = 'usuario';
    protected static ?string $pluralLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Configuración';
    //protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make()
                    ->schema([
                        Section::make('Información de usuario')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Correo')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('password')
                                    ->label(fn(string $operation) => $operation === 'edit' ? 'Nueva Contraseña' : 'Contraseña')
                                    ->password()
                                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                                    ->required(fn(string $operation): bool => $operation === 'create')
                                    ->revealable()
                                    ->dehydrated(fn($state) => filled($state)),
                            ])->columnSpan(1),
                        Section::make('Información de miembro')
                            ->schema([

                                Select::make('miembro_id')
                                    ->relationship(
                                        'miembro',
                                        'nombre_miembro',
                                        function ($query) {
                                            return $query->where('estado', true);
                                        }
                                    )
                                    ->searchable()
                                    ->preload(),

                                FileUpload::make('foto')
                                    ->image()
                                    ->avatar()
                                    ->directory('usuarios')
                                    ->imageEditor(),
                                Forms\Components\Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),

                            ])->columnSpan(1)
                    ])->columns(2),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verificación')
                    ->dateTime(),
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
                Tables\Columns\TextColumn::make('miembro.nombre_miembro'),
                Tables\Columns\ImageColumn::make('foto')
                    ->circular(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Verify')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->action(function (User $user) {
                        $user->email_verified_at = Date('Y-m-d H:i:s');
                        $user->save();
                    }),
                Action::make('Unverify')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(function (User $user) {
                        $user->email_verified_at = null;
                        $user->save();
                    }),
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
