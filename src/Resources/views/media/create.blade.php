@extends('admin.layouts.app', ['breadcrumbs'=>['Home'=>route('home'),'Image'=>route('image.index'),'Create'=>'']])
@section('content')
    <div class="container">
        <h2 class="text-center">{{ __('Add Image') }}</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center mb-4">
                                <label for="image" class="col-md-7 col-form-label ">{{ __('Image') }}</label>

                                <div class="col-md-7">
                                    <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" autofocus>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>
                                    <a href="{{route('image.index')}}" type="submit" class="btn btn-outline-secondary ms-2">
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


