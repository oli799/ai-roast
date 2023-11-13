@extends('layouts.app')

@section('content')
<div class="max-w-screen-xl mx-auto px-5">
    @if(!empty($payment->roast))
    @php
    $roast = json_decode($payment->roast, true);
    @endphp

    <p>{{$roast['first_impression']}}</p>

    <div class="grid sm:grid-cols-2 md:grid-cols-3 mt-16 gap-16">
        @foreach($roast['topics'] as $topic)
        <div>
            <p>{{ucfirst(str_replace('_',' ',$topic['topic_name']))}}</p>

            @foreach ($topic['subtopics'] as $subtopic)
            <p>{{ucfirst(str_replace('_',' ',$subtopic['subtopic_name']))}}</p>
            <small>{{$subtopic['feedback']}}</small>
            @endforeach
        </div>

        <i>{{$topic['advice']}}</i>
        @endforeach
    </div>

    <p>{{$roast['final_thoughts']}}</p>

    @else
    <h1
        class="font-extrabold text-transparent text-4xl md:text-6xl bg-clip-text bg-gradient-to-r from-red-300 to-red-600 -rotate-3 text-center">
        Your review is on the way!
    </h1>
    @endif
</div>
@endsection
