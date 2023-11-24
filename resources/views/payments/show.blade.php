@extends('layouts.app')

@push('scripts')
@if(!$payment->parsed_at)
<script>
    setInterval(() => {
        window.location.reload();
    }, 3000);
</script>
@endif
@endpush

@section('content')
<div class="max-w-screen-xl mx-auto px-5 space-y-10">
    @if(!$payment->paid_at)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Please complete your payment process to unlock your roast!</h2>
        <a  href="{{route('payments.redirect', $payment)}}" class="btn btn-primary">Pay 9.99$</a>
    </div>
    @else
    @if($payment->parsed_at && $payment->roast)
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
        <h1 class="text-3xl text-center italic">{{$payment->roast['first_impression']}}</h1>
    </section>

    <section class="flex flex-col items-center space-y-5">
        <p class="secondary-content">Details:</p>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
            @foreach($payment->roast['topics'] as $topic)
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
        <h1 class="text-3xl text-center italic">{{$payment->roast['final_thoughts']}}</h1>
    </section>
    @elseif($payment->parsed_at && !$payment->roast)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Something went worng!</h2>
        <p>please get in touch with me:  <a class="text-secondary" href="mailto:reider340@gmail.com">reider340@gmail.com</a></p>
    </div>
    @else
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Roasting your page...</h2>
        <span class="loading loading-spinner loading-lg"></span>
    </div>
    @endif
    @endif
</div>
@endsection
