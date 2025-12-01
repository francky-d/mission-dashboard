<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->color(fn (UserRole $state): string => $state->color())
                    ->formatStateUsing(fn (UserRole $state): string => $state->label())
                    ->sortable(),
                IconColumn::make('suspended_at')
                    ->label('Statut')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn (User $record): bool => $record->isSuspended()),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options(UserRole::class),
                TernaryFilter::make('suspended_at')
                    ->label('Statut')
                    ->placeholder('Tous')
                    ->trueLabel('Suspendus')
                    ->falseLabel('Actifs')
                    ->nullable(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('suspend')
                    ->label('Suspendre')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Suspendre l\'utilisateur')
                    ->modalDescription('Êtes-vous sûr de vouloir suspendre cet utilisateur ? Il ne pourra plus se connecter.')
                    ->visible(fn (User $record): bool => ! $record->isSuspended() && $record->id !== Auth::id())
                    ->action(function (User $record): void {
                        $record->suspend();
                        Notification::make()
                            ->title('Utilisateur suspendu')
                            ->success()
                            ->send();
                    }),
                Action::make('unsuspend')
                    ->label('Réactiver')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Réactiver l\'utilisateur')
                    ->modalDescription('Êtes-vous sûr de vouloir réactiver cet utilisateur ?')
                    ->visible(fn (User $record): bool => $record->isSuspended())
                    ->action(function (User $record): void {
                        $record->unsuspend();
                        Notification::make()
                            ->title('Utilisateur réactivé')
                            ->success()
                            ->send();
                    }),
                Action::make('delete')
                    ->label('Supprimer')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Supprimer l\'utilisateur')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Cette action est irréversible.')
                    ->visible(fn (User $record): bool => $record->id !== Auth::id())
                    ->action(function (User $record): void {
                        $record->delete();
                        Notification::make()
                            ->title('Utilisateur supprimé')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Supprimer'),
                ]),
            ]);
    }
}
