<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages\ManagePayments;
use App\Jobs\CreateRoast;
use App\Mail\RoastCreated;
use App\Models\Payment;
use Exception;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
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
                Toggle::make('parseable')
                    ->required()
                    ->default(false),

                Toggle::make('listable')
                    ->required()
                    ->default(false),

                DateTimePicker::make('paid_at')
                    ->nullable()
                    ->native(false),

                TextInput::make('uuid')
                    ->disabled()
                    ->autofocus()
                    ->required(),

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

                TextInput::make('computer_image_url')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com'),

                TextInput::make('phone_image_url')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com'),

                Textarea::make('roast')
                    ->columnSpan(2)
                    ->autosize()
                    ->json(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('listable')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parseable')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('paid_at')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parse_started')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('parsed_at')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_sent_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
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

                Action::make('regenerateRoast')
                    ->label('Regenerate Roast')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (Payment $payment): bool => empty($payment->email_sent_at) && ! empty($payment->roast))
                    ->requiresConfirmation()
                    ->action(function (Payment $payment): void {
                        $payment->update([
                            'roast' => null,
                            'parseable' => true,
                            'parse_started' => null,
                        ]);

                        CreateRoast::dispatch($payment);

                        Notification::make()
                            ->title('Starting roast regeneration!')
                            ->icon('heroicon-o-arrow-path')
                            ->success()->send();
                    }),
                EditAction::make(),
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
