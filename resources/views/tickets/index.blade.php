@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>My Tickets</h3>
            </div>
            <div class="title_right text-end">
                <a href="{{ route('tickets.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create New
                    Ticket</a>
            </div>
        </div>

        <div class="clearfix"></div>

        @if (session('success'))
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
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-responsive nowrap" id="ticketsTable"
                                width="100%">
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
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td><strong>{{ $ticket->ticket_id }}</strong></td>
                                            <td>{{ $ticket->title }}</td>
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
                                            <td>{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('tickets.show', $ticket) }}"
                                                        class="btn btn-info text-white" title="View Details"><i
                                                            class="fa fa-eye"></i></a>

                                                    @if (($ticket->status == 'CLOSE' || $ticket->status == 'CLOSED') && $ticket->user_id == Auth::id())
                                                        <button type="button" class="btn btn-success text-white btn-verify"
                                                            data-id="{{ $ticket->id }}" title="Verify Resolution"><i
                                                                class="fa fa-check"></i></button>
                                                        <button type="button" class="btn btn-danger text-white btn-reopen"
                                                            data-id="{{ $ticket->id }}" title="Reopen Ticket"><i
                                                                class="fa fa-undo"></i></button>
                                                        {{-- Forms remain the same --}}
                                                        <form id="form-verify-{{ $ticket->id }}"
                                                            action="{{ route('tickets.verify', $ticket) }}" method="POST"
                                                            style="display:none;">
                                                            @csrf
                                                        </form>
                                                        <form id="form-reopen-{{ $ticket->id }}"
                                                            action="{{ route('tickets.reopen', $ticket) }}" method="POST"
                                                            style="display:none;">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </div>
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
                    responsive: true,
                    order: [
                        [5, 'desc']
                    ]
                });

                // Verify Confirmation
                $('.btn-verify').on('click', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Verify Resolution?',
                        text: "Are you sure you are satisfied with the solution provided?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#26B99A',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Verify!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form-verify-' + id).submit();
                        }
                    });
                });

                // Reopen Confirmation
                $('.btn-reopen').on('click', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Reopen Ticket?',
                        text: "Are you sure you want to reopen this ticket? Use this if your issue hasn't been resolved.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d9534f',
                        cancelButtonColor: '#999',
                        confirmButtonText: 'Yes, Reopen!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form-reopen-' + id).submit();
                        }
                    });
                });
            });
        </script>
    @endsection
