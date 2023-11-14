<main class="grid lg:grid-cols-2 place-items-center pt-16 pb-16 md:pt-8">
    <div class="py-6 md:order-1 hidden md:block">
        <img src="{{asset('images/hero.svg')}}" alt="hero image" />
    </div>
    <div>
        <h1 class="text-5xl lg:text-6xl xl:text-7xl font-bold lg:tracking-tight">
            Tailored Roasts for your Landing Page with the help of AI
        </h1>
        <p class="text-lg mt-4 text-slate-600 max-w-xl">
            We roast your landing page to help you improve conversion, SEO and overall user experience.
        </p>
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <form id="payment-form" class="w-full lg:w-2/3" action="{{route('payments.store')}}" method="post">
                @csrf
                <div class="mb-5">
                    <input type="text" placeholder="Name" name="name"
                        class="w-full px-4 py-2 border-2 rounded-md outline-none focus:ring-4 border-gray-300 focus:border-gray-600 ring-gray-100">
                    @if($errors->has('name'))
                    <p class="text-sm text-red-500 mt-1">{{$errors->first('name')}}</p>
                    @endif
                </div>
                <div class="mb-5">
                    <input type="url" placeholder="Website URL" name="url"
                        class="w-full px-4 py-2 border-2 rounded-md outline-none focus:ring-4 border-gray-300 focus:border-gray-600 ring-gray-100">
                    @if($errors->has('url'))
                    <p class="text-sm text-red-500 mt-1">{{$errors->first('url')}}</p>
                    @endif
                </div>
                <div class="mb-5">
                    <div id="card-element" class="bg-gray-100 p-3 rounded-md"></div>
                    <div id="card-errors" role="alert" class="text-sm text-red-500 mt-1"></div>
                    @if($errors->has('payment'))
                    <p class="text-sm text-red-500 mt-1">{{$errors->first('payment')}}</p>
                    @endif
                </div>

                <button
                    class="bg-black p-3 w-full rounded-md text-white font-bold enabled:hover:bg-gray-600 transition-all duration-300 disabled:opacity-50 ">
                    Roast my landing page ({{ config('stripe.charge_amount') }}$)
                </button>
            </form>
        </div>
    </div>
</main>
