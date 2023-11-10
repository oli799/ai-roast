@extends('layouts.app')

@section('content')
<div class="flex flex-col space-y-10 max-w-xl">

    @if(!empty($payment->roast))
    <x-markdown>
        {{$payment->roast}}
    </x-markdown>
    @else
    <h1
        class="font-extrabold text-transparent text-4xl md:text-6xl bg-clip-text bg-gradient-to-r from-red-300 to-red-600 -rotate-3 text-center">
        Your review is on the way!
    </h1>
    @endif
</div>
@endsection
