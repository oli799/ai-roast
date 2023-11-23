@extends('layouts.app')

@section('content')
<div class="max-w-screen-xl mx-auto px-5 space-y-10">
    @if(!empty($payment->roast))
    @php
    $roast = json_decode($payment->roast, true);
    @endphp

    <label class="swap">
        <input type="checkbox" />

        <div class="mockup-browser bg-base-300 swap-off" style="max-height: 30rem;">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>
            <div class="overflow-y-scroll" style="max-height: 30rem;">
                <img class="object-cover w-full object-top" src="{{$payment->computer_image_url}}">
            </div>
        </div>

        <div class="mockup-phone swap-on">
            <div class="camera"></div>
            <div class="display">
                <div class="artboard phone-1 overflow-y-auto">
                    <img src="{{$payment->computer_image_url}}" cla>
                </div>
            </div>
        </div>

    </label>

    <section class="w-full text-center flex flex-col items-center space-y-5">
        <p class="secondary-content">First Impression:</p>
        <h1 class="text-3xl text-center italic">{{$roast['first_impression']}}</h1>
    </section>

    <section class="flex flex-col items-center space-y-5">
        <p class="secondary-content">Details:</p>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
            @foreach($roast['topics'] as $topic)
            <div class="card w-full bg-base-200 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">{{ucfirst(str_replace('_',' ',$topic['topic_name']))}}</h2>
                    @foreach ($topic['subtopics'] as $subtopic)
                    <p class="text-sm">{{ucfirst(str_replace('_',' ',$subtopic['subtopic_name']))}}</p>
                    <small class="text-xs">{{$subtopic['feedback']}}</small>
                    @endforeach
                </div>

                <div class="card-actions">
                    <p class="text-xs">{{$topic['advice']}}</p>
                </div>
            </div>
            @endforeach

        </div>
    </section>

    <section class="w-full text-center flex flex-col items-center space-y-5">
        <p class="secondary-content">Final thoughts:</p>
        <h1 class="text-3xl text-center italic">{{$roast['final_thoughts']}}</h1>
    </section>

    @else
    <h1
        class="font-extrabold text-transparent text-4xl md:text-6xl bg-clip-text bg-gradient-to-r from-red-300 to-red-600 -rotate-3 text-center">
        Your review is on the way!
    </h1>
    @endif
</div>
@endsection
