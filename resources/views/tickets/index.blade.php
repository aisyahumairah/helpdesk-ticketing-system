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
                                        <tr class="data-row" id="row-{{ $loop->iteration }}">
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
                                            <td>{{ $ticket->created_at->format('d M Y, H:i:s') }}</td>
                                            <td>
                                                <a href="{{ route('tickets.show', $ticket) }}"
                                                    class="btn btn-info bg-blue text-white" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if ($ticket->status == 'CLOSE' && $ticket->user_id == Auth::id())
                                                    <a href="{{ route('tickets.verify', $ticket->id) }}"
                                                        class="btn btn-success bg-green text-white btn-verify"
                                                        title="Verify Resolution">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    <a href="{{ route('tickets.reopen', $ticket->id) }}"
                                                        class="btn btn-danger bg-red text-white btn-reopen"
                                                        title="Reopen Ticket">
                                                        <i class="fa fa-undo"></i>
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
    @endsection

    @section('script')
        <script>
            // Use a function to ensure code runs after jQuery is ready
            function initIndexPage() {
                if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                    setTimeout(initIndexPage, 100);
                    return;
                }

                const table = $('#ticketsTable').DataTable({
                    responsive: true,
                    order: [
                        [5, 'desc']
                    ]
                });

                // Handle Verify button click with SweetAlert2 confirmation
                $(function() {
                    $(document).on('click', '.btn-verify', function(e) {
                        e.preventDefault();

                        // Fetch the assign URL
                        var assignUrl = $(this).attr("href");

                        // Get the ID of the row that will be removed
                        var rowId = $(this).closest('.data-row').attr('id');

                        // Configure SweetAlert2 with Bootstrap buttons
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: "ms-3 btn btn-success",
                                cancelButton: "btn btn-danger ms-3"
                            },
                            buttonsStyling: false
                        });

                        // Display confirmation dialog
                        swalWithBootstrapButtons.fire({
                            title: "Verify Ticket",
                            text: "Are you sure you are satisfied with the solution provided?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, Verify!",
                            cancelButtonText: "No!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform AJAX request for assigning
                                $.ajax({
                                    url: assignUrl,
                                    type: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    success: function(response) {
                                        console.log(response); // Log the response

                                        // Show success message
                                        Swal.fire("Success!",
                                            "The ticket has been successfully verified.",
                                            "success");

                                        // Remove the deleted row from the DOM
                                        $('#' + rowId).fadeOut(300, function() {
                                            $(this).remove();

                                            setTimeout(function() {
                                                location.reload();
                                            }, 300); // 1000 ms = 1 second delay
                                        });

                                        // Optionally reload the page or update UI
                                        // setTimeout(function() {
                                        //     location.reload();
                                        // }, 1000); // Uncomment if you want to reload
                                    },
                                    error: function(xhr, status, error) {
                                        console.log("Error:", error); // Log the error

                                        // Display error message
                                        Swal.fire("Error!",
                                            "The ticket verification failed. Please try again.",
                                            "error"
                                        );
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                swalWithBootstrapButtons.fire({
                                    title: "Cancelled",
                                    text: "Ticket verification cancelled.",
                                    icon: "error"
                                });
                            }
                        });
                    });
                });

                // Handle Reopen button click with SweetAlert2 confirmation
                $(function() {
                    $(document).on('click', '.btn-reopen', function(e) {
                        e.preventDefault();

                        // Fetch the assign URL
                        var assignUrl = $(this).attr("href");

                        // Get the ID of the row that will be removed
                        var rowId = $(this).closest('.data-row').attr('id');

                        // Configure SweetAlert2 with Bootstrap buttons
                        const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: "ms-3 btn btn-success",
                                cancelButton: "btn btn-danger ms-3"
                            },
                            buttonsStyling: false
                        });

                        // Display confirmation dialog
                        swalWithBootstrapButtons.fire({
                            title: 'Reopen Ticket?',
                            text: "Are you sure you want to reopen this ticket? Use this if your issue hasn't been resolved.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, Reopen!",
                            cancelButtonText: "No!",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform AJAX request for assigning
                                $.ajax({
                                    url: assignUrl,
                                    type: 'PATCH',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    success: function(response) {
                                        console.log(response); // Log the response

                                        // Show success message
                                        Swal.fire("Success!",
                                            "The ticket has been successfully reopened.",
                                            "success");

                                        // Remove the deleted row from the DOM
                                        $('#' + rowId).fadeOut(300, function() {
                                            $(this).remove();

                                            setTimeout(function() {
                                                location.reload();
                                            }, 300); // 1000 ms = 1 second delay
                                        });

                                        // Optionally reload the page or update UI
                                        // setTimeout(function() {
                                        //     location.reload();
                                        // }, 1000); // Uncomment if you want to reload
                                    },
                                    error: function(xhr, status, error) {
                                        console.log("Error:", error); // Log the error

                                        // Display error message
                                        Swal.fire("Error!",
                                            "The ticket reopening failed. Please try again.",
                                            "error"
                                        );
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                swalWithBootstrapButtons.fire({
                                    title: "Cancelled",
                                    text: "Ticket reopening cancelled.",
                                    icon: "error"
                                });
                            }
                        });
                    });
                });


            }

            $(document).ready(function() {
                initIndexPage();
            });
        </script>
    @endsection
