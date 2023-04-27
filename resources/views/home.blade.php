@extends('layouts.app')

@push('scripts')
@vite('resources/js/payment.js')
@endpush

@section('content')
<div class="flex flex-col space-y-10 max-w-xl w-1/2">
    <form id="payment-form" action="{{route('payments.store')}}" method="post">
        @csrf
        <div class="mb-5">
            <label for="name" class="font-medium text uppercase block">Your name</label>
            <input type="text" name="name" id="name" required
                class="mt-1 w-full p-3 rounded-xl border-2 border-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @if($errors->has('name'))
            <p class="text-sm text-red-500 mt-1">{{$errors->first('name')}}</p>
            @endif
        </div>
        <div class="mb-5">
            <label for="email" class="font-medium text uppercase block">Your email</label>
            <input type="email" name="email" id="email" required
                class="mt-1 w-full p-3 rounded-xl border-2 border-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @if($errors->has('email'))
            <p class="text-sm text-red-500 mt-1">{{$errors->first('email')}}</p>
            @endif
        </div>
        <div class="mb-5">
            <label for="url" class="font-medium text uppercase block">Your website</label>
            <input type="url" name="url" id="url" required
                class="mt-1 w-full p-3 rounded-xl border-2 border-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @if($errors->has('url'))
            <p class="text-sm text-red-500 mt-1">{{$errors->first('url')}}</p>
            @endif
        </div>
        <div class="mb-10">
            <label for="email" class="font-medium text uppercase block">Payment details</label>
            <div id="card-element" class="bg-gray-100 p-5 rounded-xl"></div>
            <div id="card-errors" role="alert" class="text-sm text-red-500 mt-1"></div>
            @if($errors->has('payment'))
            <p class="text-sm text-red-500 mt-1">{{$errors->first('payment')}}</p>
            @endif
        </div>
        <button
            class="bg-red-500 p-3 w-full rounded-xl text-white uppercase font-bold enabled:hover:bg-red-600 transition-all duration-300 disabled:opacity-50 ">
            Roast my landing page 🔥
        </button>
    </form>

    <div class="w-full text-center">
        <small class="text-gray-400">
            {{config('app.name')}} provides reliable and fast feedback to help you maximize your website's
            potential.
            Our
            cost-effective service will identify areas of improvement and provide you with specific recommendations
            to
            improve your website's design and user experience, helping you achieve your website goals.
        </small>
    </div>
</div>
@endsection
