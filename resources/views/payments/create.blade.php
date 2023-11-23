@extends('layouts.app')

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
@vite('resources/js/payment.js')
@endpush

@section('content')
<div class="px-4 pt-4">
    <div class="mb-8 text-center">
        <h2 class="mb-4 text-4xl font-extrabold tracking-tight">Welcome back 👋</h2>
        <div class="text-base opacity-80">Ready to tick your habits?</div>
    </div>

    <div class="card bg-base-200 max-w-xl mx-auto mb-8 md:mb-12">
        <div class="card-body">
            <form method="POST" action="{{route('payments.create')}}" id="payment-form">
                <x-input name="name" />

                <x-input name="email" type="email" />

                <x-input name="url" type="url" />

                <div class="form-control w-full mt-5">
                    <div id="card-element" class="input input-ghost input-bordered w-full p-3"></div>
                    <div id="card-errors" role="alert" class="text-sm text-red-500 mt-1"></div>
                </div>

                <div class="form-group pt-4">
                    <button type="submit" class="btn btn-block btn-primary">Roast my landing page</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
