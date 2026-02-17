@extends('layout.induk')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Roles Management</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Roles List</h2>
                        <ul class="nav navbar-right panel_toolbox">
                             @can('role.create')
                            <li><a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary text-white"><i class="fa fa-plus"></i> Create New Role</a></li>
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
                                                <th>Permissions</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roles as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ ucfirst($role->name) }}</td>
                                                <td>
                                                    @foreach($role->permissions->take(5) as $permission)
                                                        <span class="badge badge-info">{{ $permission->name }}</span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 5)
                                                        <span class="badge badge-secondary">+{{ $role->permissions->count() - 5 }} more</span>
                                                    @endif
                                                </td>
                                                <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                    @can('role.update')
                                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                                    @endcan
                                                    @can('role.delete')
                                                    @if(!in_array($role->name, ['admin', 'it_support', 'user']))
                                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                    @else
                                                    <button class="btn btn-secondary btn-sm" disabled><i class="fa fa-trash"></i></button>
                                                    @endif
                                                    @endcan
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
            window.confirmAction('Are you sure you want to delete this role?', function() {
                form.submit();
            });
        });
    });
</script>
@endsection
