<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Reset Password</title>

    <!-- Compiled CSS (Bootstrap 5 & Icons) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 10px;
            cursor: pointer;
            z-index: 10;
            color: #73879C;
        }

        #password-strength li {
            margin-bottom: 2px;
        }
    </style>
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <h1>Reset Password</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <input type="email" name="email" class="form-control" placeholder="Email" required=""
                                value="{{ old('email') }}" />
                        </div>

                        <div class="position-relative">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="New Password" required="" autocomplete="new-password" />
                            <span class="toggle-password" onclick="togglePassword('password')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>

                        <div class="mb-3" id="password-strength" style="display: none;">
                            <div class="progress mb-2" style="height: 6px;">
                                <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <ul class="list-unstyled small mb-3">
                                <li id="length" class="text-danger"><i class="fa fa-times me-1"></i> 8-12 characters
                                </li>
                                <li id="upper" class="text-danger"><i class="fa fa-times me-1"></i> One uppercase
                                    (A-Z)</li>
                                <li id="lower" class="text-danger"><i class="fa fa-times me-1"></i> One lowercase
                                    (a-z)</li>
                                <li id="number" class="text-danger"><i class="fa fa-times me-1"></i> One number (0-9)
                                </li>
                                <li id="symbol" class="text-danger"><i class="fa fa-times me-1"></i> One symbol
                                    (@$!%*#?&.)</li>
                            </ul>
                        </div>

                        <div class="position-relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Confirm New Password" required="" />
                            <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" id="submit-btn" class="btn btn-primary submit">Reset
                                Password</button>
                        </div>

                        <div class="separator">
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        $(document).ready(function() {
            $('#password').on('focus', function() {
                $('#password-strength').slideDown();
            });

            $('#password').on('input', function() {
                const password = $(this).val();
                let strength = 0;

                const criteria = {
                    length: password.length >= 8 && password.length <= 12,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[@$!%*#?&.]/.test(password)
                };

                Object.keys(criteria).forEach(key => {
                    const el = $('#' + key);
                    if (criteria[key]) {
                        el.removeClass('text-danger').addClass('text-success');
                        el.find('i').removeClass('fa-times').addClass('fa-check');
                        strength++;
                    } else {
                        el.removeClass('text-success').addClass('text-danger');
                        el.find('i').removeClass('fa-check').addClass('fa-times');
                    }
                });

                const bar = $('#strength-bar');
                const percent = (strength / 5) * 100;
                bar.css('width', percent + '%');

                if (strength <= 2) {
                    bar.removeClass('bg-warning bg-success').addClass('bg-danger');
                } else if (strength <= 4) {
                    bar.removeClass('bg-danger bg-success').addClass('bg-warning');
                } else {
                    bar.removeClass('bg-danger bg-warning').addClass('bg-success');
                }

                // Optional: Enable/Disable button based on strength if you want to strictly enforce frontend
                // $('#submit-btn').prop('disabled', strength < 5);
            });
        });
    </script>
</body>

</html>
