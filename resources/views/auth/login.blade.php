<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Login</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background: #F7F7F7;
            font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
        }

        .login_wrapper {
            max-width: 500px;
            margin: 8% auto 0;
        }

        .login_content {
            padding: 30px 40px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #2A3F54;
        }

        .login_content h1 {
            font-size: 26px;
            margin-bottom: 25px;
            text-align: center;
            color: #73879C;
            font-weight: 500;
        }

        .form-control {
            margin-bottom: 20px;
            padding: 10px 15px;
            border-radius: 3px;
            border: 1px solid #ced4da;
        }

        .btn-primary {
            background-color: #2A3F54;
            border-color: #2A3F54;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #1a2a3a;
            border-color: #1a2a3a;
        }

        .separator {
            border-top: 1px solid #D8D8D8;
            margin-top: 25px;
            padding-top: 20px;
            text-align: center;
            color: #73879C;
        }

        .login_content a {
            color: #2A3F54;
            text-decoration: none;
            font-weight: 500;
        }

        .login_content a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <h1>Login Form</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <input type="email" name="email" class="form-control" placeholder="Email" required=""
                                value="{{ old('email') }}" />
                        </div>
                        <div>
                            <input type="password" name="password" class="form-control" placeholder="Password"
                                required="" />
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary submit">Log in</button>
                        </div>

                        <div class="separator">
                            <div class="d-grid gap-2">
                                <a href="{{ route('auth.google') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-google"></i> Login with Google
                                </a>
                            </div>

                            <p class="change_link mt-3">
                                <a href="{{ route('password.request') }}" class="to_register"> Lost your password? </a>
                                <br>
                                New to site? <a href="{{ route('register') }}" class="to_register"> Create Account </a>
                            </p>

                            <div class="clearfix"></div>
                            <br />

                            <div>
                                <h1><i class="bi bi-headset"></i> {{ config('app.name') }}</h1>
                                <p>©{{ date('Y') }} All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>

</html>
