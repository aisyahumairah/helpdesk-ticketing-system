@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User Management</h3>
            </div>
            <div class="title_right text-end">
                @can('user.create')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Add New User
                    </a>
                @endcan
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>System Users</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered datatable dt-responsive nowrap" id="usersTable"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr class="data-row" id="row-{{ $loop->iteration }}">
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-info">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $user->created_at->format('d M Y H:i:s') }}</td>
                                            <td class="d-flex gap-2">
                                                @can('user.update')
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                        class="btn btn-sm btn-info text-white">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('user.reset_password')
                                                    <a class="reset btn btn-sm btn-warning text-white"
                                                        href="{{ route('admin.users.reset_password', $user) }}"
                                                        title="Reset Password">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                @endcan


                                                @if ($user->id !== Auth::id())
                                                    @can('user.delete')
                                                        <a class="delete btn btn-sm btn-danger"
                                                            href="{{ route('admin.users.destroy', $user) }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // SweetAlert2 confirmation for delete button
        $(function() {
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();

                // Fetch the delete URL and row ID
                var deleteUrl = $(this).attr("href");
                var rowId = $(this).closest('.data-row').attr('id');
                console.log("Delete URL:", deleteUrl);

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
                    title: "Are you sure you want to delete this record?",
                    text: "You will not be able to restore this record!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, continue!",
                    cancelButtonText: "No!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform AJAX request for deletion
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log(response); // Log the response

                                // Show success message
                                Swal.fire("Success!", "Your record has been deleted.",
                                    "success");

                                // Remove the deleted row from the DOM
                                $('#' + rowId).fadeOut(300, function() {
                                    $(this).remove();

                                    setTimeout(function() {
                                        location.reload();
                                    }, 300); // 1000 ms = 1 second delay
                                });

                                // Optionally reload the page if necessary
                                // setTimeout(function() {
                                //     location.reload();
                                // }, 1000); // Uncomment if you want to reload
                            },
                            error: function(xhr, status, error) {
                                console.log("Error:", error); // Log the error

                                // Display error message
                                Swal.fire("Error!",
                                    "Failed to delete record. Please try again.",
                                    "error");
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Cancelled",
                            text: "Your record is safe :)",
                            icon: "error"
                        });
                    }
                });
            });
        });

        //reset password to default
        $(document).on('click', '.reset', function(e) {
            e.preventDefault();

            // Assume that the user ID is stored in the button's data-id attribute
            var userId = $(this).data('id');

            // SweetAlert2 for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "You will reset the password to the default password: abc123",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reset!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    var resetUrl = $(this).attr("href");
                    return $.ajax({
                        url: resetUrl,
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Optionally reload or update the page after success
                        setTimeout(() => {
                            location.reload(); // Reload the page to reflect changes
                        }, 1500);

                    }).fail(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.responseJSON.error ||
                                'Failed to reset password',
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });
    </script>
@endsection
