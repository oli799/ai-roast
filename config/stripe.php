<?php

return [
    'key' => env('STRIPE_KEY', ''),
    'secret' => env('STRIPE_SECRET', ''),
    'currency' => env('STRIPE_CURRENCY', 'huf'),
    'charge_amount' => env('STRIPE_CHARGE_AMOUNT', 400),
];
