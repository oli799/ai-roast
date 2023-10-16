@extends('layouts.app')

@push('scripts')
@vite('resources/js/payment.js')
@endpush

@section('content')
<section class="relative flex md:flex-row flex-col-reverse items-center justify-center gap-10 md:gap-20 my-40">
    <div class="flex flex-col space-y-10 w-full md:w-1/2 xl:w-1/3">
        <form class="w-full" id="payment-form" action="{{route('payments.store')}}" method="post">
            @csrf
            <div class="mb-5">
                <label for="name" class="font-medium text uppercase block">Name</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 w-full p-3 rounded-xl border-2 border-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @if($errors->has('name'))
                <p class="text-sm text-red-500 mt-1">{{$errors->first('name')}}</p>
                @endif
            </div>
            <div class="mb-5">
                <label for="email" class="font-medium text uppercase block">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 w-full p-3 rounded-xl border-2 border-gray-200 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @if($errors->has('email'))
                <p class="text-sm text-red-500 mt-1">{{$errors->first('email')}}</p>
                @endif
            </div>
            <div class="mb-5">
                <label for="url" class="font-medium text uppercase block">Landing Page URL</label>
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
                Roast my landing page ({{ config('stripe.charge_amount') }}$) 🔥
            </button>
        </form>
    </div>

    <div class="w-full md:w-1/2 xl:w-2/3">
        <h1
            class="font-display leading-squished tracking-tight text-6xl md:text-8xl md:leading-squished lg:text-8xl lg:leading-squished xl:text-11xl xl:leading-squished">
            <span class="text-gray-900">Instant,<br>automated<br><span
                    class="text-red-500">Laravel<br>upgrades</span></span>
        </h1>
        <div
            class="mt-12 sm:max-w-sm md:max-w-md lg:max-w-xl text-lg leading-relaxed sm:text-xl sm:leading-relaxed lg:text-xl lg:leading-relaxed 2xl:text-3xl 2xl:leading-relaxed xl:max-w-2xl text-gray-900 font-medium">
            <p>You have better things to do than upgrade Laravel. Let bots do all the <b>shifty work</b> for you.</p>
        </div>
    </div>

    <x-bottom-right-lights />
</section>

<section class="text-gray-900">
    <div class="mb-20">
        <h2 class="leading-none font-display tracking-tight text-4xl sm:text-5xl md:text-6xl xl:text-7xl">
            Sure you could upgrade<br>manually, <span class="text-gray-900">but...</span>
        </h2>

        <ul class="mt-12 md:mt-24 grid gap-12 md:gap-14 md:grid-cols-2">

            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl font-semibold tracking-tight">You often push off doing the upgrade</p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>


                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Shift will open a <abbr
                            title="Pull Request (or Merge Request on GitLab)">PR</abbr> with nice, atomic commits for
                        you to review in just a few clicks.</p>
                </div>
            </li>
            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>

                    <p class="text-lg md:text-xl font-semibold tracking-tight">You only do the minimum changes</p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Shift goes above and beyond the
                        <i>Upgrade Guide</i>, automating even the smallest changes.
                    </p>
                </div>
            </li>
            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl font-semibold tracking-tight">You end up in dependency hell</p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Shift bumps dependencies for core
                        packages and popular community packages too.</p>
                </div>
            </li>
            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl font-semibold tracking-tight">You miss something that wastes hours</p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Shift leaves detailed comments about
                        changes catered for your app.</p>
                </div>
            </li>
            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl font-semibold tracking-tight">You don't take the opportunity to
                        refactor</p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Shift does more than just upgrades with
                        tools like the <a href="/laravel-code-fixer" class="underline hover:text-gray-400">Laravel
                            Fixer</a> and <a href="https://laravelshift.com/workbench"
                            class="underline hover:text-gray-400">Workbench</a>.</p>
                </div>
            </li>
            <li class="space-y-2">
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0  text-red-500 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl font-semibold tracking-tight">You have more important things to work on
                    </p>
                </div>
                <div class="flex space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="shrink-0 text-gray-900 h-8 w-8">
                        <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-lg md:text-xl tracking-tight text-gray-900">Ain't nobody got time for upgrading. <a
                            href="https://laravelshift.com/shifts" class="underline hover:text-gray-400">Let Shift
                            upgrade Laravel</a> for you.</p>
                </div>
            </li>
        </ul>
    </div>
</section>
@endsection
