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
                    <div class="x_content">
                        <form action="{{ route('support.audit_trails') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control"
                                            value="{{ date('Y-m-01') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">Events</label>
                                        <select name="event_type" class="form-control select2">
                                            <option value="">All Events</option>
                                            @foreach ($eventTypes as $eventType)
                                                <option value="{{ $eventType }}"
                                                    {{ request('event_type') == $eventType ? 'selected' : '' }}>
                                                    {{ $eventType }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-sm-6">
                                        <label class="form-label">Users</label>
                                        <select name="user_id" class="form-control select2">
                                            <option value="">All Users</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 mt-3 text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-filter"></i>
                                        Filter
                                    </button>
                                    <a href="{{ route('support.audit_trails') }}" class="btn btn-secondary">
                                        <i class="fa fa-sync"></i>
                                        Reset
                                    </a>
                                </div>
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
                                    <th>Description</th>
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
                                        <td>
                                            @if (is_array($trail->details))
                                                {{ $trail->details['message'] ?? $trail->event }}
                                                @if ((isset($trail->details['changes']) && count($trail->details['changes']) > 0) || isset($trail->details['data']))
                                                    <br>
                                                    <button type="button" class="btn btn-xs btn-outline-primary mt-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-audit-{{ $trail->id }}">
                                                        <i class="fa fa-search-plus"></i> View Changes
                                                    </button>
                                                @endif
                                            @else
                                                {{ $trail->details }}
                                            @endif
                                        </td>
                                        <td><small>{{ $trail->ip_address }}</small></td>
                                        <td>
                                            @if ($trail->ticket_id)
                                                <a href="{{ route('tickets.show', $trail->ticket_id) }}"
                                                    class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i>
                                                    Ticket
                                                </a>
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

    <!-- Modals Section -->
    @foreach ($trails as $trail)
        @if (is_array($trail->details) &&
                ((isset($trail->details['changes']) && count($trail->details['changes']) > 0) ||
                    isset($trail->details['data'])))
            <div class="modal fade" id="modal-audit-{{ $trail->id }}" tabindex="-1" aria-hidden="true"
                style="color: #333;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Event Details: {{ $trail->event }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <p><strong>Description:</strong> {{ $trail->details['message'] ?? 'N/A' }}</p>

                            @if (isset($trail->details['data']))
                                <hr>
                                <h6>Created Data:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trail->details['data'] as $field => $value)
                                                <tr>
                                                    <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                                    <td>{{ is_array($value) ? json_encode($value) : $value ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @if (isset($trail->details['changes']) && count($trail->details['changes']) > 0)
                                <hr>
                                <h6>Field Changes:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Old Value</th>
                                                <th>New Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trail->details['changes'] as $field => $change)
                                                <tr>
                                                    <td class="fw-bold">{{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                                    <td class="text-danger">
                                                        {{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-success">
                                                        {{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
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
