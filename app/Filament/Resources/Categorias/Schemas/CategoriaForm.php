<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre de la categoría')
                    ->helperText('Ingrese el nombre de la categoría')
                    ->placeholder('Ej: Categoria')
                    //->disabled()
                    ->unique()
                    ->required()
                    ->validationMessages([
                        'unique' => 'Ya existe una categoría registrada con ese nombre'
                ]),
                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->helperText('Ingrese una descripción para la categoría')
                    ->placeholder('Ej: Descripción de la categoría')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
