@extends('layouts.app')

@push('scripts')
@vite('resources/js/payment.js')
@endpush

@section('content')
<section
    class="pt-4 pb-12 md:pt-24 md:pb-12 md:mb-12 flex flex-col md:flex-row justify-center items-center md:items-start space-y-20 md:space-y-0 md:space-x-6 lg:space-x-12 text-center md:text-left">
    <div class="w-full md:w-7/12 md:mr-8">
        <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-6">
            Big headline
            <span class="text-primary whitespace-nowrap">with color text.</span>
        </h1>
        <p class="mb-4 text-lg opacity-90">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
            asd
        </p>
        <div class="inline-flex flex-col justify-center items-center my-5 gap-2">
            <a class="btn btn-primary btn-wide">CTA button</a>
            <a onclick="document.getElementById('faq').scrollIntoView({ behavior: 'smooth', block: 'end' ,
                inline: 'nearest' })" class="btn btn-ghost btn-wide">How it
                works?</a>
        </div>
    </div>

    <div class="relative w-full md:w-5/12 flex flex-col justify-start items-start gap-16 md:gap-24">

    </div>
</section>

<section class="grid gap-12 md:gap-24 text-center md:text-left pb-12 md:pb-24">
    <div class="text-center">
        <h2 class="font-extrabold text-4xl md:text-5xl tracking-tight mb-4 md:mb-6">Lorem ipsum dolor sit amet
            consectetur.</h2>
        <p class="md:text-lg opacity-90">Lorem ipsum, dolor sit amet consectetur adipisicing elit.
            Cupiditate, impedit.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-5">
        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>
            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>

        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>

            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>

        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>

            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>

        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>

            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>
        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>

            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>
        <div class="mockup-browser bg-base-300 cursor-pointer hover:opacity-30 transition-all duration-300">
            <div class="mockup-browser-toolbar">
                <div class="input">https://daisyui.com</div>
            </div>

            <img class="object-cover" src="https://hinicio.com/wp-content/uploads/2022/08/placeholder-3.png">
        </div>
    </div>
</section>

<section class="max-w-4xl mx-auto" id="faq">
    <h2 class="text-3xl font-extrabold tracking-tight text-center mb-12 md:mb-24">Frequently asked questions</h2>

    <div class="collapse collapse-plus bg-base-200 mb-5">
        <input type="radio" name="my-accordion-3" checked="checked" />
        <div class="collapse-title text-xl font-medium">
            Click to open this one and close others
        </div>
        <div class="collapse-content">
            <p>hello</p>
        </div>
    </div>

    <div class="collapse collapse-plus bg-base-200 mb-5">
        <input type="radio" name="my-accordion-3" />
        <div class="collapse-title text-xl font-medium">
            Click to open this one and close others
        </div>
        <div class="collapse-content">
            <p>hello</p>
        </div>
    </div>

    <div class="collapse collapse-plus bg-base-200 mb-5">
        <input type="radio" name="my-accordion-3" />
        <div class="collapse-title text-xl font-medium">
            Click to open this one and close others
        </div>
        <div class="collapse-content">
            <p>hello</p>
        </div>
    </div>

    <div class="collapse collapse-plus bg-base-200 mb-5">
        <input type="radio" name="my-accordion-3" />
        <div class="collapse-title text-xl font-medium">
            Click to open this one and close others
        </div>
        <div class="collapse-content">
            <p>hello</p>
        </div>
    </div>

    <div class="collapse collapse-plus bg-base-200 mb-5">
        <input type="radio" name="my-accordion-3" />
        <div class="collapse-title text-xl font-medium">
            Click to open this one and close others
        </div>
        <div class="collapse-content">
            <p>hello</p>
        </div>
    </div>
</section>

<section class="card bg-primary text-primary-content w-full md:w-[32rem] mx-auto mt-10 md:mt-20">
    <div class="card-body items-center text-center gap-6">
        <h3 class="card-title">Beat procrastination today</h3>
        <p class="opacity-90">Grow a wonderful garden!</p>
        <div class="card-actions justify-center"><a class="btn btn-wide" href="/welcome">Build good
                habits now</a></div>
    </div>
</section>
@endsection
