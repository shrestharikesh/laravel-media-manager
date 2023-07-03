<label for="{{$name}}" class="col-form-label ">{{ __('Image') }}</label>
<input
    id="{{$name}} {{$id ?? ''}}"
    type="{{$type ?? 'file'}}"
    class="form-control @error($name) is-invalid @enderror {{$class ?? ''}}"
    name="{{$name}}"
    {{isset($multiple) && $multiple ? 'multiple' :''}}
    {{isset($required) && $required ? 'required' :''}}
    autofocus
>
@error($name)
<span class="invalid-feedback" role="alert">
    <strong>{{ $message }}</strong>
</span>
@enderror
