<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages\ManagePayments;
use App\Jobs\CreateRoast;
use App\Mail\RoastCreated;
use App\Models\Payment;
use Exception;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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

                MarkdownEditor::make('roast')
                    ->required()
                    ->placeholder('Roast')
                    ->columnSpan(2),
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

            ])
            ->actions([
                Action::make('regenerateRoast')
                    ->label('Regenerate Roast')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (Payment $payment): bool => empty($payment->email_sent_at) && ! empty($payment->roast))
                    ->requiresConfirmation()
                    ->action(function (Payment $payment): void {
                        $payment->update([
                            'roast' => null,
                        ]);

                        CreateRoast::dispatch($payment);

                        Notification::make()
                            ->title('Starting roast regeneration!')
                            ->icon('heroicon-o-arrow-path')
                            ->success()->send();
                    }),

                Action::make('sendEmail')
                    ->label('Send Email')
                    ->icon('heroicon-o-envelope')
                    ->visible(fn (Payment $payment): bool => empty($payment->email_sent_at))
                    ->requiresConfirmation()
                    ->action(function (Payment $payment): void {
                        try {
                            Mail::to($payment->email)->send(new RoastCreated($payment));

                            Notification::make()
                                ->title('Email sent!')
                                ->icon('heroicon-o-envelope')
                                ->success()->send();
                        } catch (Exception) {
                            Notification::make()
                                ->title('Error while sending email!')
                                ->icon('heroicon-o-envelope')
                                ->danger()->send();
                        }
                    }),
                EditAction::make(),
                ViewAction::make(),
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
}
