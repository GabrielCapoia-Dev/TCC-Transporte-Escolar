<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnderecoResource\Pages;
use App\Filament\Resources\EnderecoResource\RelationManagers;
use App\Models\Endereco;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnderecoResource extends Resource
{
    protected static ?string $model = Endereco::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = "Gerenciamento";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(6)->schema([
                    Forms\Components\TextInput::make('cep')
                        ->label('CEP')
                        ->required()
                        ->columnSpan(2),


                    Forms\Components\TextInput::make('complemento')
                        ->label('Complemento')
                        ->columnSpan(2),


                    Forms\Components\TextInput::make('numero')
                        ->label('Número')
                        ->columnSpan(2),

                ]),
                Grid::make(6)->schema([

                    Forms\Components\TextInput::make('logradouro')
                        ->label('Logradouro')
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('bairro')
                        ->label('Bairro')
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('cidade')
                        ->label('Cidade')
                        ->required()
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('uf')
                        ->label('UF')
                        ->required()
                        ->columnSpan(1),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('logradouro')
                    ->label('Logradouro')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bairro')
                    ->label('Bairro')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cidade')
                    ->label('Cidade')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('uf')
                    ->label('UF')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cep')
                    ->label('CEP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('complemento')
                    ->label('Complemento')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
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
            'index' => Pages\ManageEnderecos::route('/'),
        ];
    }
}
