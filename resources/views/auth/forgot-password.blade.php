<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Forgot Password</title>
    
    <!-- Compiled CSS (Bootstrap 5 & Icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background: #F7F7F7; font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif; }
        .login_wrapper { max-width: 400px; margin: 8% auto 0; }
        .login_content { padding: 30px 40px; background: #fff; border-radius: 5px; box-shadow: 0 0 15px rgba(0,0,0,0.1); border-top: 5px solid #2A3F54; }
        .login_content h1 { font-size: 26px; margin-bottom: 25px; text-align: center; color: #73879C; font-weight: 500; }
        .form-control { margin-bottom: 20px; padding: 10px 15px; border-radius: 3px; border: 1px solid #ced4da; }
        .btn-primary { background-color: #2A3F54; border-color: #2A3F54; padding: 10px; font-weight: 500; }
        .btn-primary:hover { background-color: #1a2a3a; border-color: #1a2a3a; }
        .separator { border-top: 1px solid #D8D8D8; margin-top: 25px; padding-top: 20px; text-align: center; color: #73879C; }
        .login_content a { color: #2A3F54; text-decoration: none; font-weight: 500; }
        .login_content a:hover { text-decoration: underline; }
    </style>
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <h1>Reset Password</h1>
                        <p class="text-muted text-center">Enter your email and we'll send you a link to reset your password.</p>
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <input type="email" name="email" class="form-control" placeholder="Email" required="" value="{{ old('email') }}" />
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary submit">Send Reset Link</button>
                        </div>
                        
                        <div class="separator">
                            <p class="change_link mt-3">
                                Remembered your password? <a href="{{ route('login') }}" class="to_register"> Log in </a>
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
