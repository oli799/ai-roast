<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $payments = Payment::query()
            ->where('listable', true)
            ->whereNotNull('parsed_at')
            ->whereNotNull('roast')
            ->get();

        return view('home', ['payments' => $payments]);
    }
}
