<?php

namespace App\Observers;

use App\Jobs\CreateRoast;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment)
    {
        CreateRoast::dispatch($payment);
    }
}
