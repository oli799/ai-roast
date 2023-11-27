@extends('layouts.app')

@push('scripts')
@if(!$payment->parsed_at)
<script>
    setInterval(() => {
        window.location.reload();
    }, 3000);
</script>
@endif

<script>
    const payButton = document.getElementById('pay_button');

    if (payButton) {
        payButton.addEventListener('click', (e) => {
            axios.post('/payment/{{$payment->uuid}}/pay', {
                _token: '{{csrf_token()}}'
            }).then((response) => {
                if (response.data.success) {
                    window.open('http://stackoverflow.com', '_blank');
                }
            }).catch((error) => {
                console.log(error);
            });
        });
    }

</script>
@endpush

@section('content')
<div class="max-w-screen-xl mx-auto px-5 space-y-10">
    @if(!$payment->paid_at)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Please complete your payment
            process to unlock your roast!</h2>
        <button id="pay_button" href="#" class="btn btn-primary">Pay 9.99$</button>
    </div>
    @else
    @if($payment->parsed_at && $payment->roast)
    <section class="w-full text-center flex flex-col items-center space-y-5">
        <p class="secondary-content">First Impression:</p>
        <h1 class="text-3xl text-center italic">{{$payment->roast['first_impression']}}</h1>
    </section>

    <section class="flex flex-col space-y-10">
            @foreach($payment->roast['topics'] as $topic)
            <div class="flex flex-col space-y-4">
                <div>
                    <h2 class="font-extrabold text-2xl md:text-3xl tracking-tight mb-3">{{ucfirst(str_replace('_',' ',$topic['topic_name']))}}</h2>
                    <p class="md:text-lg opacity-90"><span class="font-bold">Advice:</span> {{$topic['advice']}}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach ($topic['subtopics'] as $subtopic)
                        <div class="flex-1 w-full card card-compact md:card-normal bg-base-200 text-left">
                            <div class="card-body flex-row md:flex-col items-center md:items-start gap-4 md:gap-8">
                                <span class="text-5xl md:text-6xl">{{$subtopic['emoji']}}</span>
                                <div>
                                    <div class="card-title pb-2">{{ucfirst(str_replace('_',' ',$subtopic['subtopic_name']))}}</div>
                                    <div class="italic opacity-80">
                                        <div>{{$subtopic['feedback']}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
    </section>

    <section class="w-full text-center flex flex-col items-center space-y-5">
        <p class="secondary-content">Final thoughts:</p>
        <h1 class="text-3xl text-center italic">{{$payment->roast['final_thoughts']}}</h1>
    </section>
    @elseif($payment->parsed_at && !$payment->roast)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Something went worng!</h2>
        <p>please get in touch with me: <a class="text-secondary"
                href="mailto:reider340@gmail.com">reider340@gmail.com</a></p>
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
