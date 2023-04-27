@extends('layouts.app')

@section('content')
<div class="flex flex-col space-y-10 max-w-xl">
    <h1
        class="font-extrabold text-transparent text-4xl md:text-6xl bg-clip-text bg-gradient-to-r from-red-300 to-red-600 -rotate-3 text-center">
        Your review is on the way!
    </h1>

    <div style="width:100%;height:0;padding-bottom:97%;position:relative; border-radius: 10px;"><iframe
            src="https://giphy.com/embed/QWRtAK9uifSKEZx7LX" width="100%" height="100%"
            style="position:absolute; border-radius: 12px;" frameBorder="0" class="giphy-embed"
            allowFullScreen></iframe></div>

    <div class="w-full text-center">
        <small class="text-gray-400">
            We will also send you it's ready 🔥
        </small>
    </div>
</div>
@endsection
