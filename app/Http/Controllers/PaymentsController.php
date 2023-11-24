<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentsController extends Controller
{
    public function show(Payment $payment): View
    {
        return view('payments.show', ['payment' => $payment]);
    }

    public function create(): View
    {
        return view('payments.create');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255'],
            'url' => ['required', 'max:255', 'url'],
        ]);

        $payment = Payment::query()->create([
            'name' => request()->input('name'),
            'url' => request()->input('url'),
            'email' => request()->input('email'),
        ]);

        return redirect()->back()->with([
            'success' => 'Your website roast will be available at the following link after the payment within 10 minutes.',
            'url' => route('payments.show', ['payment' => $payment]),
        ]);
    }

    public function redirect(Payment $payment): RedirectResponse
    {
        $payment->update([
            'paid_at' => now(),
        ]);

        return redirect()->away('https://buy.stripe.com/8wMaEQ92n2Eq9Mc144');
    }
}
