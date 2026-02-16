@extends('layout.induk')

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Dashboard</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Welcome to Helpdesk System</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>Welcome back, <strong>{{ Auth::user()->name }}</strong>!</p>
                    <p>This is your dashboard where you can manage your tickets and support requests.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
