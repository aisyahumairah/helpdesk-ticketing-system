@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Change Password</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Update Security Credentials</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('password.change.update') }}" method="POST"
                            class="form-horizontal form-label-left">
                            @csrf

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="current_password">Current
                                    Password <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 position-relative">
                                    <input type="password" id="current_password" name="current_password" required
                                        class="form-control">
                                    <span class="toggle-password position-absolute"
                                        style="right: 25px; top: 10px; cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="password">New Password
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 position-relative">
                                    <input type="password" id="password" name="password" required class="form-control"
                                        autocomplete="new-password">
                                    <span class="toggle-password position-absolute"
                                        style="right: 25px; top: 10px; cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>

                                    <div class="mt-2" id="password-strength">
                                        <div class="progress" style="height: 10px;">
                                            <div id="strength-bar" class="progress-bar" role="progressbar"
                                                style="width: 0%;"></div>
                                        </div>
                                        <ul class="list-unstyled mt-2 small">
                                            <li id="length" class="text-danger"><i class="fa fa-times me-1"></i> 8-12
                                                characters</li>
                                            <li id="upper" class="text-danger"><i class="fa fa-times me-1"></i> At least
                                                one uppercase letter (A-Z)</li>
                                            <li id="lower" class="text-danger"><i class="fa fa-times me-1"></i> At least
                                                one lowercase letter (a-z)</li>
                                            <li id="number" class="text-danger"><i class="fa fa-times me-1"></i> At least
                                                one number (0-9)</li>
                                            <li id="symbol" class="text-danger"><i class="fa fa-times me-1"></i> At least
                                                one special character (@$!%*#?&.)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align"
                                    for="password_confirmation">Confirm Password <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 position-relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                        class="form-control">
                                    <span class="toggle-password position-absolute"
                                        style="right: 25px; top: 10px; cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group text-center">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success" id="submit-btn" disabled>Change
                                        Password</button>
                                    <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Toggle Password Visibility
            $('.toggle-password').on('click', function() {
                const input = $(this).siblings('input');
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Password Strength Checker
            $('#password').on('input', function() {
                const password = $(this).val();
                let strength = 0;

                // Validation Criteria
                const criteria = {
                    length: password.length >= 8 && password.length <= 12,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[@$!%*#?&.]/.test(password)
                };

                // Update UI for each criterion
                Object.keys(criteria).forEach(key => {
                    const element = $('#' + key);
                    if (criteria[key]) {
                        element.removeClass('text-danger').addClass('text-success');
                        element.find('i').removeClass('fa-times').addClass('fa-check');
                        strength++;
                    } else {
                        element.removeClass('text-success').addClass('text-danger');
                        element.find('i').removeClass('fa-check').addClass('fa-times');
                    }
                });

                // Update Strength Bar
                const bar = $('#strength-bar');
                const percentage = (strength / 5) * 100;
                bar.css('width', percentage + '%');

                if (strength <= 2) {
                    bar.removeClass('bg-success bg-warning').addClass('bg-danger');
                } else if (strength <= 4) {
                    bar.removeClass('bg-danger bg-success').addClass('bg-warning');
                } else {
                    bar.removeClass('bg-danger bg-warning').addClass('bg-success');
                }

                // Enable/Disable submit button
                if (strength === 5) {
                    $('#submit-btn').prop('disabled', false);
                } else {
                    $('#submit-btn').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
