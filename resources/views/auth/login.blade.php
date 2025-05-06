@extends('layouts.app')

@php
    $isAuth = true;
@endphp

@section('content_auth')
    <div class="d-flex flex-column justify-content-center align-items-center" style="width: 100vw; height: 100vh">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header fw-bold fs-1 text-center">{{ __('Masuk') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3 d-flex flex-column justify-content-center">
                                    <label for="name" class=" text-start">{{ __('Nama') }}</label>

                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4 d-flex flex-column justify-content-center">
                                    <label for="password" class=" text-start">{{ __('Sandi') }}</label>

                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Sandi">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mx-auto w-100 d-flex justify-content-center"> 
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Masuk') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection