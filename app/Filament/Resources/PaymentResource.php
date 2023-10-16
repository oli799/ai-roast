<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages\ManagePayments;
use App\Models\Payment;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('John Doe'),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->placeholder('Email'),

                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com'),

                RichEditor::make('roast')
                    ->required()
                    ->placeholder('Roast'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roast')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_sent_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('regenerateRoast')
                    ->label('Regenerate Roast')
                    ->icon('heroicon-o-arrow-path')
                    ->action('regenerateRoast')
                    ->visible(fn (Payment $payment): bool => empty($payment->email_sent_at))
                    ->requiresConfirmation(),

                Action::make('sendEmail')
                    ->label('Send Email')
                    ->icon('heroicon-o-envelope')
                    ->action('sendEmail')
                    ->visible(fn (Payment $payment): bool => empty($payment->email_sent_at))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePayments::route('/'),
        ];
    }

    public function regenerateRoast(): void
    {
        Notification::make()
            ->title('Starting roast regeneration!')
            ->icon('heroicon-o-arrow-path')
            ->success();
    }

    public function sendEmail(): void
    {
        Notification::make()
            ->title('Email sent!')
            ->icon('heroicon-o-envelope')
            ->success();
    }
}
