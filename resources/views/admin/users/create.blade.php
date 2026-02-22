@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Create User</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_content">
                        <form action="{{ route('admin.users.store') }}" method="POST"
                            class="form-horizontal form-label-left">
                            @csrf

                            <div class="form-group row mb-3">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Full Name <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Email Address <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Roles</label>
                                <div class="col-md-6 col-sm-6">
                                    <select name="roles[]" class="form-control select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ strtolower($role->name) === 'user' ? 'selected' : '' }}>
                                                {{ strtoupper($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">If no role is selected, the <strong>USER</strong> role will be
                                        assigned automatically.</small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Initial Password</label>
                                <div class="col-md-6 col-sm-6 position-relative">
                                    <input type="password" id="password" name="password" class="form-control">
                                    <span class="toggle-password position-absolute"
                                        style="right: 25px; top: 10px; cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <small class="text-muted d-block mt-1">Leave blank to use the system default password:
                                        <strong>abc123</strong>. The user will be required to change it on first
                                        login.</small>

                                    <div class="mt-2" id="password-strength" style="display: none;">
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

                            <div class="form-group row mb-3">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Confirm Password</label>
                                <div class="col-md-6 col-sm-6 position-relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control">
                                    <span class="toggle-password position-absolute"
                                        style="right: 25px; top: 10px; cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group row">
                                <div class="col-md-9 col-sm-9 offset-md-3">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" id="submit-btn" class="btn btn-success">Create User</button>
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

                if (password.length > 0) {
                    $('#password-strength').show();
                } else {
                    $('#password-strength').hide();
                    $('#submit-btn').prop('disabled', false); // Enable if blank (optional)
                    return;
                }

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
                // Only disable if there is input but it's not strong enough
                if (strength === 5) {
                    $('#submit-btn').prop('disabled', false);
                } else {
                    $('#submit-btn').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
