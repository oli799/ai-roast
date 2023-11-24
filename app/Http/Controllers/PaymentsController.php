<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PaymentsController extends Controller
{
    public function show(Payment $payment): View
    {
        $payment->roast = json_decode((string) $payment->roast, null, 512, JSON_THROW_ON_ERROR);

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

    public function pay(Payment $payment): JsonResponse
    {
        $payment->update([
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
