<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentsController extends Controller
{
    public function index(): View
    {
        if (! $token = request('token') ?? false) {
            abort(404);
        }

        if (! Payment::query()->where('token', $token)->exists()) {
            abort(404);
        }

        return view('success');
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
                'description' => 'Ki fizetett '.config('stripe.charge_amount').' forintot',
                'metadata' => ['integration_check' => 'accept_a_payment'],
                'confirm' => true,
                'receipt_email' => request()->input('email'),
            ]);

            Payment::query()->create([
                'name' => request()->input('name'),
                'email' => request()->input('email'),
                'token' => $token = Str::uuid(),
                'url' => request()->input('url'),
                'stripe_id' => 'abc',
            ]);

            return redirect('/payments?token='.$token);
        } catch (CardException) {
            return redirect('/')->withErrors([
                'payment' => 'There was a problem with your payment.',
            ])->withInput();
        }
    }
}
