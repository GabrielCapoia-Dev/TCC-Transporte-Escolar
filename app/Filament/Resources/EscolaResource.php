<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EscolaResource\Pages;
use App\Filament\Resources\EscolaResource\RelationManagers;
use App\Models\Escola;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EscolaResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        $value = (string) static::getModel()::count();

        if ($value > 0) {
            return $value;
        }
        return null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Quantidade de escolas cadastradas';
    }

    protected static ?string $model = Escola::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required(),
                Forms\Components\TextInput::make('endereco_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('complemento'),
                Forms\Components\TextInput::make('numero'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('endereco_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('complemento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListEscolas::route('/'),
            'create' => Pages\CreateEscola::route('/create'),
            'edit' => Pages\EditEscola::route('/{record}/edit'),
        ];
    }
}
