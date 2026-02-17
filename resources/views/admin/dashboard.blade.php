@extends('layout.induk')

@section('content')
<div class="">
    <div class="row" style="display: inline-block;">
        <div class="tile_count d-flex flex-wrap">
            <div class="col-md-6 col-sm-6 tile_stats_count px-4">
                <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
                <div class="count">{{ $stats['total_users'] }}</div>
            </div>
            <div class="col-md-6 col-sm-6 tile_stats_count px-4">
                <span class="count_top"><i class="fa fa-ticket-alt"></i> Total Tickets</span>
                <div class="count green">{{ $stats['total_tickets'] }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Recent Audit Activities</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="list-unstyled msg_list">
                        @foreach($stats['recent_audits'] as $audit)
                            <li class="pb-2 border-bottom mb-2">
                                <div>
                                    <strong>{{ $audit->event }}</strong> by {{ $audit->user->name ?? 'System' }}
                                    <span class="pull-right text-muted"><small>{{ $audit->created_at->diffForHumans() }}</small></span>
                                </div>
                                <div class="message">{{ $audit->details }}</div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="text-center mt-3">
                        <a href="{{ route('support.audit_trails') }}" class="btn btn-sm btn-primary">View All Activities</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Quick Links</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-app"><i class="fa fa-users"></i> Users</a>
                    <a href="{{ route('admin.settings') }}" class="btn btn-app"><i class="fa fa-cogs"></i> Settings</a>
                    <a href="{{ route('support.dashboard') }}" class="btn btn-app"><i class="fa fa-headset"></i> Support Dash</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .tile_count .tile_stats_count .count { font-size: 30px; font-weight: 600; line-height: 47px; }
    .green { color: #26B99A !important; }
</style>
@endsection
