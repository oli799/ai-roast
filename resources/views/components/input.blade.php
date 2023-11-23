@props([
'name',
'type' => 'text',
])

<div class="form-control w-full">
    <label class="label" for="{{strtolower($name)}}">
        <span class="label-text">{{ucfirst($name)}}</span>
    </label>
    <input type="{{$type}}" placeholder="{{ucfirst($name)}}" name="{{strtolower($name)}}" id="{{strtolower($name)}}"
        class="input input-ghost input-bordered w-full @if($errors->has(strtolower($name))) input-error @endif" />
    @if($errors->has(strtolower($name)))
    <label class="label">
        <span class="label-text-alt">{{$errors->first(strtolower($name))}}</span>
    </label>
    @endif
</div>
