@extends('layout.induk')

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>My Tickets</h3>
        </div>
        <div class="title_right text-end">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create New Ticket</a>
        </div>
    </div>

    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Ticket List</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered" id="ticketsTable">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Urgency</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td><strong>{{ $ticket->ticket_id }}</strong></td>
                                    <td>{{ $ticket->title }}</td>
                                    <td>{{ $ticket->categoryCode->name ?? $ticket->category }}</td>
                                    <td>
                                        @php
                                            $urgencyClass = match($ticket->urgency) {
                                                'HIGH', 'CRIT' => 'badge-danger',
                                                'MED' => 'badge-warning',
                                                default => 'badge-info'
                                            };
                                        @endphp
                                        <span class="badge {{ $urgencyClass }}">{{ $ticket->urgencyCode->name ?? $ticket->urgency }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($ticket->status) {
                                                'NEW' => 'badge-primary',
                                                'PEND' => 'badge-warning',
                                                'CLOSE', 'DONE' => 'badge-success',
                                                'REOPEN' => 'badge-dark',
                                                default => 'badge-info'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $ticket->statusCode->name ?? $ticket->status }}</span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info text-white"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            order: [[5, 'desc']]
        });
    });
</script>
@endsection
