<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $payments = Cache::rememberForever('home-rostasts', fn () => Payment::query()->whereIn('id', range(1, 4))->get());

        return view('home', ['payments' => $payments]);
    }
}
