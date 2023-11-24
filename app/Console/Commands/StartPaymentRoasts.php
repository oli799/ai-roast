<?php

namespace App\Console\Commands;

use App\Jobs\CreateRoast;
use App\Models\Payment;
use Illuminate\Console\Command;

class StartPaymentRoasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-payment-roasts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $payments = Payment::query()->where('parseable', true)->whereNull('parse_started')->get();

        $payments->each(function (Payment $payment): void {
            $payment->update([
                'parse_started' => now(),
            ]);

            CreateRoast::dispatch($payment);
        });
    }
}
