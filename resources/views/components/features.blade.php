@props([
'payments' => $payments,
])

<div class="mt-16 md:mt-0">
    <h2 class="text-4xl lg:text-5xl font-bold lg:tracking-tight">
        Everything you need to start a website
    </h2>
    <p class="text-lg mt-4 text-slate-600">
        Astro comes batteries included. It takes the best parts of state-of-the-art
        tools and adds its own innovations.
    </p>
</div>

<div class="grid sm:grid-cols-2 md:grid-cols-3 mt-16 gap-16">
    @foreach ($payments as $payment)
    <a href="{{route('payments.show', ['payment' => $payment])}}" class="shrink-0 relative group cursor-pointer">
        <img class="transition-all duration-300 group-hover:opacity-25 w-full max-h-96 rounded-md object-cover mx-auto"
            src="{{$payment->computer_image_url}}" alt="{{$payment->url}}" />

        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="transition-all duration-300 hidden absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 group-hover:flex w-10 h-10">
            <path fill-rule="evenodd"
                d="M12 1.5a.75.75 0 01.75.75V4.5a.75.75 0 01-1.5 0V2.25A.75.75 0 0112 1.5zM5.636 4.136a.75.75 0 011.06 0l1.592 1.591a.75.75 0 01-1.061 1.06l-1.591-1.59a.75.75 0 010-1.061zm12.728 0a.75.75 0 010 1.06l-1.591 1.592a.75.75 0 01-1.06-1.061l1.59-1.591a.75.75 0 011.061 0zm-6.816 4.496a.75.75 0 01.82.311l5.228 7.917a.75.75 0 01-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 01-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 01-1.247-.606l.569-9.47a.75.75 0 01.554-.68zM3 10.5a.75.75 0 01.75-.75H6a.75.75 0 010 1.5H3.75A.75.75 0 013 10.5zm14.25 0a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H18a.75.75 0 01-.75-.75zm-8.962 3.712a.75.75 0 010 1.061l-1.591 1.591a.75.75 0 11-1.061-1.06l1.591-1.592a.75.75 0 011.06 0z"
                clip-rule="evenodd" />
        </svg>
    </a>
    @endforeach
</div>
