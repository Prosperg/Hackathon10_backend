@extends('layouts.auth')

@section('title')
    Se connecter | Admin
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="bg-primary bg-soft">
        <div class="row">
            <div class="col-7">
                <div class="text-primary p-4">
                    <h5 class="text-primary">Bienvenu !</h5>
                    <p>Veuillez entrer vos indentifiant pour continuer la session.</p>
                </div>
            </div>
            <div class="col-5 align-self-end">
                <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="card-body pt-0"> 
        <div class="auth-logo">
            <a href="index.html" class="auth-logo-light">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{asset('assets/images/logo-light.svg')}}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>

            <a href="index.html" class="auth-logo-dark">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{asset('assets/images/logo.svg')}}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>
        </div>
        <div class="p-2">
            <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="username" placeholder="Entrer votre email"
                    value="{{old('email')}}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group auth-pass-inputgroup">
                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" aria-label="Password" 
                        value="{{old('password')}}" aria-describedby="password-addon">
                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" name="remember" type="checkbox" id="remember-check"
                    {{old('remember') ? 'checked' : ''}}>
                    <label class="form-check-label" for="remember-check">
                        Se souvener de moi
                    </label>
                </div>
                
                <div class="mt-3 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Connecter</button>
                </div>

                <div class="mt-4 text-center">
                    <h5 class="font-size-14 mb-3">Se connecter avec</h5>

                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="javascript::void()" class="social-list-item bg-primary text-white border-primary">
                                <i class="mdi mdi-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript::void()" class="social-list-item bg-info text-white border-info">
                                <i class="mdi mdi-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript::void()" class="social-list-item bg-danger text-white border-danger">
                                <i class="mdi mdi-google"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="mt-4 text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Mot de passe oublié ?</a>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>
<div class="mt-5 text-center">
    
    <div>
        <p>Pas de compte ? <a href="{{ route('register') }}" class="fw-medium text-primary"> Créer maintenant </a> </p>
        <p>© <script>document.write(new Date().getFullYear())</script> Dr. Justin  DONGBEHOUNDE</p>
    </div>
</div>
@endsection
