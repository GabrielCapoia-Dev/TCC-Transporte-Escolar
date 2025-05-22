<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RotasTransporteResource\Pages;
use App\Filament\Resources\RotasTransporteResource\RelationManagers;
use App\Models\RotasTransporte;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RotasTransporteResource extends Resource
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
        return 'Quantidade de rotas cadastradas';
    }

    protected static ?string $model = RotasTransporte::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageRotasTransportes::route('/'),
        ];
    }
}
