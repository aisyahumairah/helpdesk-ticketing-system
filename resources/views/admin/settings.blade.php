@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>System Settings</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Configuration Manager</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        {{-- Tab Navigation --}}
                        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                    data-bs-target="#general" type="button" role="tab">
                                    <i class="fa fa-cog me-1"></i> General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security"
                                    type="button" role="tab">
                                    <i class="fa fa-shield me-1"></i> Security
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="smtp-tab" data-bs-toggle="tab" data-bs-target="#smtp"
                                    type="button" role="tab">
                                    <i class="fa fa-envelope me-1"></i> SMTP / Email
                                </button>
                            </li>
                        </ul>

                        <form action="{{ route('admin.settings.update') }}" method="POST"
                            class="form-horizontal form-label-left" id="settingsForm">
                            @csrf

                            <div class="tab-content mt-4" id="settingsTabContent">

                                {{-- ===================== GENERAL TAB ===================== --}}
                                <div class="tab-pane fade show active" id="general" role="tabpanel">
                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="app_name">
                                            System Name
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="app_name" name="app_name" class="form-control"
                                                value="{{ $settings['app_name'] ?? config('app.name') }}"
                                                placeholder="e.g. Helpdesk System">
                                            <small class="text-muted">Displayed in the browser title and emails.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="support_email">
                                            Support Email
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="email" id="support_email" name="support_email"
                                                class="form-control" value="{{ $settings['support_email'] ?? '' }}"
                                                placeholder="support@yourdomain.com">
                                            <small class="text-muted">The reply-to address for outgoing support
                                                emails.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Email
                                            Notifications</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mt-2">
                                                {{-- Hidden field ensures "0" is always submitted when checkbox is unchecked --}}
                                                <input type="hidden" name="email_enabled" value="0">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="email_enabled"
                                                        name="email_enabled" value="1"
                                                        {{ ($settings['email_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                                        style="width:2.5em; height:1.3em;">
                                                    <label class="form-check-label ms-2" for="email_enabled">
                                                        Enable system-wide email notifications
                                                    </label>
                                                </div>
                                                <small class="text-muted">When off, no emails (ticket updates, assignments,
                                                    replies) will be sent.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ===================== SECURITY TAB ===================== --}}
                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <div class="alert alert-info mb-4">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Changes to session timeout, login attempts, and lockout duration take effect
                                        immediately for new sessions/login attempts.
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="session_timeout">
                                            Session Timeout <span class="text-muted">(minutes)</span>
                                        </label>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="number" id="session_timeout" name="session_timeout"
                                                    class="form-control" min="5" max="1440"
                                                    value="{{ $settings['session_timeout'] ?? '120' }}">
                                                <span class="input-group-text">min</span>
                                            </div>
                                            <small class="text-muted">Idle users are logged out after this duration. Min:
                                                5, Max: 1440 (24h).</small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="login_limit">
                                            Login Attempts Limit
                                        </label>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="number" id="login_limit" name="login_limit"
                                                    class="form-control" min="1" max="20"
                                                    value="{{ $settings['login_limit'] ?? '5' }}">
                                                <span class="input-group-text">attempts</span>
                                            </div>
                                            <small class="text-muted">Account is locked after this many consecutive failed
                                                logins.</small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"
                                            for="lockout_duration">
                                            Lockout Duration <span class="text-muted">(minutes)</span>
                                        </label>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="number" id="lockout_duration" name="lockout_duration"
                                                    class="form-control" min="1" max="1440"
                                                    value="{{ $settings['lockout_duration'] ?? '15' }}">
                                                <span class="input-group-text">min</span>
                                            </div>
                                            <small class="text-muted">Duration the account stays locked after exceeding
                                                login attempts.</small>
                                        </div>
                                    </div>
                                </div>

                                {{-- ===================== SMTP TAB ===================== --}}
                                <div class="tab-pane fade" id="smtp" role="tabpanel">
                                    <div class="alert alert-warning mb-4">
                                        <i class="fa fa-exclamation-triangle me-1"></i>
                                        <strong>Important:</strong> These settings override the <code>.env</code> mail
                                        configuration at runtime. Use the <strong>Send Test Email</strong> button to verify
                                        your settings before saving.
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_host">
                                            SMTP Host
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="smtp_host" name="smtp_host" class="form-control"
                                                value="{{ $settings['smtp_host'] ?? '' }}"
                                                placeholder="e.g. smtp.gmail.com or smtp.mailtrap.io">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_port">
                                            SMTP Port
                                        </label>
                                        <div class="col-md-3 col-sm-3">
                                            <select id="smtp_port" name="smtp_port" class="form-control">
                                                @php
                                                    $currentPort = $settings['smtp_port'] ?? '587';
                                                @endphp
                                                <option value="25" {{ $currentPort == '25' ? 'selected' : '' }}>25
                                                    (SMTP, no encryption)</option>
                                                <option value="465" {{ $currentPort == '465' ? 'selected' : '' }}>465
                                                    (SMTPS / SSL)</option>
                                                <option value="587" {{ $currentPort == '587' ? 'selected' : '' }}>587
                                                    (Submission / TLS) — recommended</option>
                                                <option value="2525" {{ $currentPort == '2525' ? 'selected' : '' }}>2525
                                                    (Alternative)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_encryption">
                                            Encryption
                                        </label>
                                        <div class="col-md-3 col-sm-3">
                                            @php $currentEnc = $settings['smtp_encryption'] ?? 'tls'; @endphp
                                            <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                                                <option value="tls" {{ $currentEnc === 'tls' ? 'selected' : '' }}>TLS
                                                    (recommended)</option>
                                                <option value="ssl" {{ $currentEnc === 'ssl' ? 'selected' : '' }}>SSL
                                                </option>
                                                <option value="" {{ $currentEnc === '' ? 'selected' : '' }}>None
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_username">
                                            SMTP Username
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="smtp_username" name="smtp_username"
                                                class="form-control" value="{{ $settings['smtp_username'] ?? '' }}"
                                                placeholder="your@email.com or SMTP username" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_password">
                                            SMTP Password
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <input type="password" id="smtp_password" name="smtp_password"
                                                    class="form-control" value="{{ $settings['smtp_password'] ?? '' }}"
                                                    placeholder="Leave blank to keep current password"
                                                    autocomplete="new-password">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="toggleSmtpPassword">
                                                    <i class="fa fa-eye" id="smtpPasswordIcon"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">For Gmail, use an <strong>App Password</strong> (not
                                                your account password).</small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"
                                            for="smtp_from_address">
                                            From Address
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="email" id="smtp_from_address" name="smtp_from_address"
                                                class="form-control" value="{{ $settings['smtp_from_address'] ?? '' }}"
                                                placeholder="noreply@yourdomain.com">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="smtp_from_name">
                                            From Name
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="smtp_from_name" name="smtp_from_name"
                                                class="form-control"
                                                value="{{ $settings['smtp_from_name'] ?? (config('app.name') ?? 'Helpdesk') }}"
                                                placeholder="e.g. Helpdesk Support">
                                        </div>
                                    </div>

                                    {{-- Test Email Section --}}
                                    <div class="ln_solid"></div>
                                    <div class="form-group row mb-3">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">
                                            <i class="fa fa-flask me-1"></i> Test SMTP
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <input type="email" id="test_email_address" class="form-control"
                                                    placeholder="Enter email to send test to..."
                                                    value="{{ auth()->user()->email }}">
                                                <button type="button" class="btn btn-info text-white"
                                                    id="sendTestEmailBtn">
                                                    <i class="fa fa-paper-plane me-1"></i> Send Test Email
                                                </button>
                                            </div>
                                            <div id="testEmailResult" class="mt-2" style="display:none;"></div>
                                            <small class="text-muted">
                                                <i class="fa fa-info-circle"></i>
                                                Save settings first, then click test to verify SMTP is working.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </div>{{-- end tab-content --}}

                            <div class="ln_solid mt-4"></div>
                            <div class="form-group row">
                                <div class="col-md-9 col-sm-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save me-1"></i> Save Settings
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>{{-- x_content --}}
                </div>{{-- x_panel --}}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Persist active tab across page reloads ──────────────────────────────
            const savedTab = sessionStorage.getItem('settingsActiveTab');
            if (savedTab) {
                const tabEl = document.querySelector('#settingsTabs button[data-bs-target="' + savedTab + '"]');
                if (tabEl) {
                    new bootstrap.Tab(tabEl).show();
                }
            }
            document.querySelectorAll('#settingsTabs button[data-bs-toggle="tab"]').forEach(function(btn) {
                btn.addEventListener('shown.bs.tab', function(e) {
                    sessionStorage.setItem('settingsActiveTab', e.target.getAttribute(
                        'data-bs-target'));
                });
            });

            // ── SMTP Password toggle ────────────────────────────────────────────────
            const toggleBtn = document.getElementById('toggleSmtpPassword');
            const pwdInput = document.getElementById('smtp_password');
            const pwdIcon = document.getElementById('smtpPasswordIcon');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (pwdInput.type === 'password') {
                        pwdInput.type = 'text';
                        pwdIcon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        pwdInput.type = 'password';
                        pwdIcon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            }

            // ── Send Test Email ─────────────────────────────────────────────────────
            const testBtn = document.getElementById('sendTestEmailBtn');
            const resultDiv = document.getElementById('testEmailResult');

            if (testBtn) {
                testBtn.addEventListener('click', function() {
                    const toEmail = document.getElementById('test_email_address').value.trim();
                    if (!toEmail) {
                        resultDiv.style.display = 'block';
                        resultDiv.innerHTML =
                            '<div class="alert alert-warning py-2 mb-0">Please enter a valid email address.</div>';
                        return;
                    }

                    testBtn.disabled = true;
                    testBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Sending...';
                    resultDiv.style.display = 'none';

                    fetch('{{ route('admin.settings.test_email') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                to: toEmail
                            }),
                        })
                        .then(r => r.json())
                        .then(data => {
                            resultDiv.style.display = 'block';
                            if (data.success) {
                                resultDiv.innerHTML =
                                    '<div class="alert alert-success py-2 mb-0"><i class="fa fa-check-circle me-1"></i>' +
                                    data.message + '</div>';
                            } else {
                                resultDiv.innerHTML =
                                    '<div class="alert alert-danger py-2 mb-0"><i class="fa fa-times-circle me-1"></i>' +
                                    data.message + '</div>';
                            }
                        })
                        .catch(err => {
                            resultDiv.style.display = 'block';
                            resultDiv.innerHTML =
                                '<div class="alert alert-danger py-2 mb-0"><i class="fa fa-times-circle me-1"></i>Network error: ' +
                                err.message + '</div>';
                        })
                        .finally(() => {
                            testBtn.disabled = false;
                            testBtn.innerHTML =
                            '<i class="fa fa-paper-plane me-1"></i> Send Test Email';
                        });
                });
            }

            // ── Auto-suggest port when encryption changes ───────────────────────────
            const encSelect = document.getElementById('smtp_encryption');
            const portSelect = document.getElementById('smtp_port');

            if (encSelect && portSelect) {
                encSelect.addEventListener('change', function() {
                    if (this.value === 'ssl') {
                        portSelect.value = '465';
                    } else if (this.value === 'tls') {
                        portSelect.value = '587';
                    } else {
                        portSelect.value = '25';
                    }
                });
            }
        });
    </script>
@endsection
