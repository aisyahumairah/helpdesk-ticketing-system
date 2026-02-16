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
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="form-horizontal form-label-left">
                        @csrf
                        
                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">System Name</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="app_name" class="form-control" value="{{ $settings['app_name'] ?? config('app.name') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Support Email</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] ?? 'support@helpdesk.com' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Session Timeout (min)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="session_timeout" class="form-control" value="{{ $settings['session_timeout'] ?? '120' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Login Attempts Limit</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="login_limit" class="form-control" value="{{ $settings['login_limit'] ?? '5' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Lockout Duration (min)</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" name="lockout_duration" class="form-control" value="{{ $settings['lockout_duration'] ?? '15' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Email Notifications</label>
                            <div class="col-md-6 col-sm-6">
                                <div class="checkbox mt-2">
                                    <label>
                                        <input type="hidden" name="email_enabled" value="0">
                                        <input type="checkbox" name="email_enabled" value="1" {{ ($settings['email_enabled'] ?? '1') == '1' ? 'checked' : '' }}> Enable system-wide email notifications
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group row">
                            <div class="col-md-9 col-sm-9 offset-md-3">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
