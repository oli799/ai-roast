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
                    window.open('https://buy.stripe.com/9AQ8yi94135c2MU8ww', '_blank');
                }
            }).catch((error) => {
                console.log(error);
            });
        });
    }
</script>
@endpush

@section('content')
<div class="max-w-screen-xl mx-auto px-5 space-y-20">
    @if(!$payment->paid_at)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Please complete your payment
            process to unlock your roast!</h2>
        <button id="pay_button" href="#" class="btn btn-primary">Pay 9.99$</button>
    </div>
    @else
    @if($payment->parsed_at && $payment->roast)
    <section class="w-full text-center flex flex-col items-center mt-20">
        <h1 class="font-kalam text-3xl text-center italic">
            <span>{{$payment->roast['first_impression']}}</span>
        </h1>
    </section>

    <div x-data="{phone: false}">
        <div class="max-h-[450px] overflow-y-auto shadow-lg rounded-xl relative mx-auto"
            :class="phone ? 'w-72' : 'w-full'">
            <label class="swap swap-rotate absolute btn btn-square btn-primary top-5 right-5">
                <input type="checkbox" x-model="phone" />

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="swap-off w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="swap-on w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                </svg>
            </label>

            <img x-show="!phone" x-cloak class="w-full h-full object-cover" src={{$payment->computer_image_url}} />

            <img x-show="phone" x-cloak class="w-full h-full object-cover" src={{$payment->phone_image_url}} />
        </div>
    </div>

    <section class="flex flex-col space-y-10">
        @foreach($payment->roast['topics'] as $topic)
        <div class="flex flex-col space-y-4">
            <div>
                <h2 class="font-extrabold text-2xl md:text-3xl tracking-tight mb-3">{{ucfirst(str_replace('_','
                    ',$topic['topic_name']))}}</h2>
                <p class="md:text-lg opacity-90"><span class="font-bold">Advice:</span> {!!nl2br($topic['advice'])!!}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($topic['subtopics'] as $subtopic)
                <div class="flex-1 w-full card card-compact md:card-normal bg-base-200 text-left">
                    <div class="card-body flex-row md:flex-col items-center md:items-start gap-4 md:gap-8">
                        <span class="text-5xl md:text-6xl">{{$subtopic['emoji']}}</span>
                        <div>
                            <div class="card-title pb-2">{{ucfirst(str_replace('_',' ',$subtopic['subtopic_name']))}}
                            </div>
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

    <section class="w-full text-center flex flex-col items-center my-20">
        <h1 class="font-kalam text-3xl text-center italic">{{$payment->roast['final_thoughts']}}</h1>
    </section>
    @elseif($payment->parsed_at && !$payment->roast)
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-center mt-12 md:mt-24 mb-5">Something went worng!</h2>
        <p>please get in touch with me: <a class="link-secondary"
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
