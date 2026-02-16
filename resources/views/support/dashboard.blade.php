@extends('layout.induk')

@section('content')
<div class="">
    <!-- top tiles -->
    <div class="row" style="display: inline-block;">
        <div class="tile_count d-flex flex-wrap">
            <div class="col-md-4 col-sm-4 tile_stats_count px-4">
                <span class="count_top"><i class="fa fa-ticket-alt"></i> New Tickets</span>
                <div class="count">{{ $stats['total_new'] }}</div>
            </div>
            <div class="col-md-4 col-sm-4 tile_stats_count px-4">
                <span class="count_top"><i class="fa fa-clock"></i> Pending Tasks</span>
                <div class="count orange">{{ $stats['total_pending'] }}</div>
            </div>
            <div class="col-md-4 col-sm-4 tile_stats_count px-4">
                <span class="count_top"><i class="fa fa-check-circle"></i> Resolved</span>
                <div class="count green">{{ $stats['total_resolved'] }}</div>
            </div>
        </div>
    </div>
    <!-- /top tiles -->

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Unassigned Tickets <small>Latest 10</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unassignedTickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_id }}</td>
                                    <td>{{ $ticket->categoryCode->name ?? $ticket->category }}</td>
                                    <td><span class="badge {{ $ticket->escalation_level > 0 ? 'badge-danger' : 'badge-secondary' }}">{{ $ticket->escalation_level }}</span></td>
                                    <td>
                                        <form action="{{ route('tickets.assign', $ticket) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Self Assign</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No unassigned tickets</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>My Assigned Tasks</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myAssignedTickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_id }}</td>
                                    <td><span class="badge {{ $ticket->escalation_level > 0 ? 'badge-danger' : 'badge-secondary' }}">{{ $ticket->escalation_level }}</span></td>
                                    <td><span class="badge badge-info">{{ $ticket->statusCode->name ?? $ticket->status }}</span></td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info text-white">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No tickets assigned to you</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .tile_count .tile_stats_count .count { font-size: 30px; font-weight: 600; line-height: 47px; }
    .orange { color: #f0ad4e !important; }
    .green { color: #26B99A !important; }
</style>
@endsection
