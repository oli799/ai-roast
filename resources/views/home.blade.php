@extends('layouts.app')

@push('scripts')
@vite('resources/js/payment.js')
@endpush

@section('content')
<div class="max-w-screen-xl mx-auto px-5">
    <x-hero />
    <x-features :payments="$payments" />
    <x-cta />
</div>
@endsection
