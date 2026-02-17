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
                            <li><a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-primary text-white"><i class="fa fa-plus"></i> Create New Permission</a></li>
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
                                            @foreach($permissions as $module => $modulePermissions)
                                                @foreach($modulePermissions as $permission)
                                                <tr>
                                                    <td>{{ $permission->id }}</td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->guard_name }}</td>
                                                    <td>{{ $permission->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>
                                                        @can('permission.update')
                                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                                        @endcan
                                                        @can('permission.delete')
                                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button>
                                                        </form>
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
        
        $('.delete-btn').on('click', function() {
            var form = $(this).closest('form');
            window.confirmAction('Are you sure you want to delete this permission?', function() {
                form.submit();
            });
        });
    });
</script>
@endsection
