@extends('layout.induk')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Permissions Management</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Permissions List</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                @can('permission.create')
                                    <li><a href="{{ route('admin.permissions.create') }}"
                                            class="btn btn-sm btn-primary text-white"><i class="fa fa-plus"></i> Create New
                                            Permission</a></li>
                                @endcan
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Guard Name</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions as $module => $modulePermissions)
                                                    @foreach ($modulePermissions as $permission)
                                                        <tr class="data-row" id="row-{{ $loop->iteration }}">
                                                            <td>{{ $permission->id }}</td>
                                                            <td>{{ $permission->name }}</td>
                                                            <td>{{ $permission->guard_name }}</td>
                                                            <td>{{ $permission->created_at->format('d-m-Y H:i:s') }}</td>
                                                            <td>
                                                                @can('permission.update')
                                                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('permission.delete')
                                                                    <a href="{{ route('admin.permissions.destroy', $permission->id) }}"
                                                                        class="delete btn btn-danger btn-sm delete-btn">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });

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
    </script>
@endsection
