@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Audit Trails</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Filter by Date</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content text-end">
                        <form action="{{ route('support.audit_trails') }}" method="GET"
                            class="row g-3 justify-content-end align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ request('date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Events List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped table-bordered" id="auditTable">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trails as $trail)
                                    <tr>
                                        <td>{{ $trail->created_at->format('d M Y, H:i:s') }}</td>
                                        <td>{{ $trail->user->name ?? 'System' }}</td>
                                        <td><span class="badge bg-info">{{ $trail->event }}</span></td>
                                        <td>{{ $trail->details }}</td>
                                        <td><small>{{ $trail->ip_address }}</small></td>
                                        <td>
                                            @if ($trail->ticket_id)
                                                @php $ticket = \App\Models\Ticket::find($trail->ticket_id); @endphp
                                                @if ($ticket)
                                                    <a href="{{ route('tickets.show', $ticket) }}"
                                                        class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i>
                                                        Ticket</a>
                                                @endif
                                            @endif
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
            $('#auditTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endsection
