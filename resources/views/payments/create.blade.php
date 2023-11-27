@extends('layouts.app')

@if(session('success'))
@push('scripts')
    <script>
        setTimeout(() => {
            success_modal.showModal();
        }, 300);
    </script>

@endpush
@endif

@section('content')
<div class="px-4 pt-4">
    @if(session('success'))
    <dialog id="success_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Registration Successful! 🌟</h3>
            <p>To access your personalized landing page roast, please complete the payment in the link bleow.</p>
            <a href="{{session('url')}}" class="btn btn-primary mt-4">Continue</a>
            <small class="block mt-4">
            <span>If the button doesn't work, please manually copy the follwing link and paste it in your browser:</span>
            <a class="link-secondary" href="{{session('url')}}">{{session('url')}}</a>
            </small>
        </div>
    </dialog>
    @endif

    <div class="mb-8 text-center">
        <h2 class="mb-4 text-4xl font-extrabold tracking-tight">Let's get started...</h2>
        <div class="text-base opacity-80">Please provide your informations</div>
    </div>

    <div class="card bg-base-200 max-w-xl mx-auto mb-8 md:mb-12">
        <div class="card-body">
            <form method="POST" action="{{route('payments.create')}}" id="payment-form">
                @csrf

                <x-input name="name" />

                <x-input name="email" type="email" />

                <x-input name="url" type="url" />

                <div class="form-control">
                    <label class="label cursor-pointer">
                      <span class="label-text">Can we list your roast in the front page?</span>
                      <input name="listable" type="checkbox" checked="checked" class="checkbox checkbox-primary" />
                    </label>
                  </div>

                <div class="form-group pt-4">
                    <button type="submit" class="btn btn-block btn-primary">Roast my Page</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
