@extends('layouts.auth')

@section('title')
    Register | Admin
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="bg-primary bg-soft">
        <div class="row">
            <div class="col-7">
                <div class="text-primary p-4">
                    <h5 class="text-primary">Bienvenu !</h5>
                    <p>Veuillez entrer vos indentifiant pour ouvrir un compte.</p>
                </div>
            </div>
            <div class="col-5 align-self-end">
                <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="card-body pt-0"> 
        <div class="auth-logo">
            <a href="#" class="auth-logo-light">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{asset('assets/images/logo-light.svg')}}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>

            <a href="#" class="auth-logo-dark">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{asset('assets/images/logo.svg')}}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>
        </div>
        <div class="p-2">
            <form class="form-horizontal" action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label for="username" class="form-label">Nom</label>
                    <input id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" value="{{ old('fname') }}" required autocomplete="name" 
                    placeholder="Entrer votre nom" autofocus>

                    @error('fname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="username" class="form-label">Prenom</label>
                    <input id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname"
                     placeholder="Entrer votre nom" value="{{ old('lname') }}" required autocomplete="lname" autofocus>

                    @error('lname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="username" class="form-label">Email</label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="username" placeholder="Entrer votre email"
                    value="{{old('email')}}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group auth-pass-inputgroup">
                        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Entrer mot passe" aria-label="Password" 
                        value="{{old('password')}}" aria-describedby="password-addon">
                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <label class="form-label"> Confirmer mot de passe</label>
                    <div class="input-group auth-pass-inputgroup">
                        <input name="password_confirmation" type="password" class="form-control" placeholder="Confirmer le mot de passe" aria-label="Password_confirmation" 
                         aria-describedby="password-addons">
                        <button class="btn btn-light" type="button" id="password-addons"><i class="mdi mdi-eye-outline"></i></button>
                    </div>
                </div>

                <div class="mt-2 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Enregistrer</button>
                </div>
                <div class="mt-4 text-center">
                    <p>Déjà de compte ? <a href="{{ route('login') }}" class="fw-medium text-primary">Se connecter </a> </p>
                </div>
                <div class="mt-2 text-center">
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
            </form>
        </div>

    </div>
</div>
<div class="mt-5 text-center">
    
    <div>
        
        <p>© <script>document.write(new Date().getFullYear())</script> Dr. Justin  DONGBEHOUNDE</p>
    </div>
</div>
@endsection

