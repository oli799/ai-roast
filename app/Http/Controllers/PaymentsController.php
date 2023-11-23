<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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
        Stripe::setApiKey(config('stripe.secret'));

        request()->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255'],
            'url' => ['required', 'max:255', 'url'],
            'payment_method' => ['required'],
        ]);

        try {
            PaymentIntent::create([
                'amount' => config('stripe.charge_amount') * 100,
                'currency' => config('stripe.currency'),
                'payment_method' => request()->input('payment_method'),
                'description' => 'Ai website roast',
                'metadata' => ['integration_check' => 'accept_a_payment'],
                'confirm' => true,
                'receipt_email' => request()->input('email'),
            ]);

            $payment = Payment::query()->create([
                'name' => request()->input('name'),
                'url' => request()->input('url'),
                'email' => request()->input('email'),
                'stripe_id' => 'abc',
            ]);

            return redirect()->route('payments.show', ['payment' => $payment]);
        } catch (CardException) {
            return redirect('/')->withErrors([
                'payment' => 'There was a problem with your payment.',
            ])->withInput();
        }
    }
}
