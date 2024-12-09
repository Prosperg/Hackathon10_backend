@extends('layouts.auth')

@section('content')
<div class="card overflow-hidden">
    <div class="bg-primary bg-soft">
        <div class="row">
            <div class="col-7">
                <div class="text-primary p-4">
                    <h5 class="text-primary">Mot de passe oublié</h5>
                    <p>Entrer votre Email et instruction serait vous envoyé !</p>
                </div>
            </div>
            <div class="col-5 align-self-end">
                <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="card-body pt-0"> 
        <div>
            <a href="index.html">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{asset('assets/images/logo.svg')}}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>
        </div>
        
        <div class="p-2">
            @if (session('status'))
                <div class="alert alert-success text-center mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form class="form-horizontal" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="useremail" class="form-label">Email</label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="useremail" placeholder="Entrer votre email"
                    autocomplete="email" autofocus required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="text-end">
                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Changer</button>
                </div>

            </form>
        </div>

    </div>
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Addresse') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
