@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>All Tickets</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Filter tickets</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('support.tickets') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Ticket ID</label>
                                <input type="text" name="ticket_id" class="form-control"
                                    value="{{ request('ticket_id') }}" placeholder="ID00001/2026">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control select2">
                                    <option value="">All Statuses</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->code }}"
                                            {{ request('status') == $status->code ? 'selected' : '' }}>{{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Year</label>
                                <input type="number" name="year" class="form-control"
                                    value="{{ request('year', date('Y')) }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('support.tickets') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Ticket List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped table-bordered" id="allTicketsTable">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Requestor</th>
                                    <th>Category</th>
                                    <th>Urgency</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td><strong>{{ $ticket->ticket_id }}</strong></td>
                                        <td>{{ $ticket->user->name }}</td>
                                        <td>{{ $ticket->categoryCode->name ?? $ticket->category }}</td>
                                        <td>
                                            @php
                                                $urgencyClass = match ($ticket->urgency) {
                                                    'HIGH', 'CRIT' => 'bg-red',
                                                    'MED' => 'bg-orange',
                                                    'LOW' => 'bg-green',
                                                    default => 'bg-blue',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $urgencyClass }}">{{ $ticket->urgencyCode->name ?? $ticket->urgency }}</span>
                                        </td>
                                        <td><span
                                                class="badge {{ $ticket->escalation_level > 0 ? 'bg-red' : 'bg-secondary' }}">{{ $ticket->escalation_level }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match ($ticket->status) {
                                                    'NEW' => 'bg-blue',
                                                    'PEND' => 'bg-orange',
                                                    'CLOSE', 'CLOSED', 'DONE' => 'bg-green',
                                                    'REOPEN' => 'bg-dark',
                                                    default => 'bg-blue',
                                                };
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }}">{{ $ticket->statusCode->name ?? $ticket->status }}</span>
                                        </td>
                                        <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                                        <td>{{ $ticket->created_at->format('d M Y H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('support.adminshow', $ticket) }}"
                                                class="btn btn-sm btn-info text-white"><i class="fa fa-eye"></i></a>
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
            $('#allTicketsTable').DataTable({
                order: [
                    [7, 'desc']
                ]
            });
        });
    </script>
@endsection
