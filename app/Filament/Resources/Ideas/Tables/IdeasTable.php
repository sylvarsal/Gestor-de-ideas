<?php

namespace App\Filament\Resources\Ideas\Tables;

use App\Models\Idea;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Icons\Heroicon;

class IdeasTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(
                fn(Builder $query) => $query->withoutGlobalScope(SoftDeletingScope::class)
            )
            ->columns([
                TextColumn::make('titulo')
                    ->searchable()
                    ->label('Idea'),
                TextColumn::make('descripcion')
                    ->limit(50)
                    ->label('Descripción'),
                TextColumn::make('estado')
                    ->badge()
                    ->colors([
                        'gray' => 'registrada',
                        'warning' => 'en revisión',
                        'success' => 'aprobada',
                        'danger' => 'rechazada',
                    ])
                    ->searchable(),
                TextColumn::make('autor')
                    ->searchable(),
                    //->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('categoria.nombre')
                    ->searchable()
                    ->label('Categoría'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creado el')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Actualizado el')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('observacion')
                   ->limit(50)
                    ->label('Observación')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'registrada' => 'Registrada',
                        'en revisión' => 'En revisión',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),

                Action::make('aprobar')
                    ->authorize('cambiarEstado')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aprobación')
                    ->modalDescription('¿Desea aprobar esta idea?')
                    ->action(function (Idea $record) {
                        $record->update([
                            'estado' => 'aprobada',
                        ]);

                        Notification::make()
                            ->title('Idea aprobada correctamente')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(Idea $record) => $record->estado !== 'aprobada'),

                Action::make('rechazar')
                   ->authorize('cambiarEstado')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Rechazar idea')
                    ->modalDescription('¿Desea rechazar esta idea? Escriba el motivo del rechazo.')
                    ->modalSubmitActionLabel('Sí, rechazar')
                    ->schema([
                        Textarea::make('observacion')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Idea $record, array $data): void {
                        $record->update([
                            'estado' => 'rechazada',
                            'observacion' => $data['observacion'],
                        ]);

                        Notification::make()
                            ->title('Idea rechazada correctamente')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn(Idea $record): bool => $record->estado === 'rechazada'),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->toolbarActions([
                BulkActionGroup::make([
                    //DeleteBulkAction::make(),
                ]),
            ]);
    }
}