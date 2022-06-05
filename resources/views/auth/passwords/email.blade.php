<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('csslo/fonts/icomoon/style.css')}}">

    <link rel="stylesheet" href="{{ asset('csslo/css/owl.carousel.min.css')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('csslo/css/bootstrap.min.css')}}">

    <!-- Style -->
    <link rel="stylesheet" href="{{ asset('csslo/css/style.css')}}">

    <title>Ecolink | Email</title>
</head>

<body>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-2">
                    <img src="{{ asset('csslo/images/undraw_file_sync_ot38.svg')}}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents" style="margin-top: 80px;">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3><strong>Ecolink</strong></h3>
                                <!-- <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p> -->
                            </div>
                            @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('status') }}
                            </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group first">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <a href="{{ url('/') }}" style="font-size: 14px;" class="form-control">Return to Login Screen</a>
                                <input type="submit" value="Send Password Reset Link" class="btn text-white btn-block btn-primary">
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script src="{{ asset('csslo/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('csslo/js/popper.min.js')}}"></script>
    <script src="{{ asset('csslo/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('csslo/js/main.js')}}"></script>
</body>

</html>