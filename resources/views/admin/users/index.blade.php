@extends('layout.induk')

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>User Management</h3>
        </div>
        <div class="title_right text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New User</a>
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
                    <table class="table table-striped table-bordered" id="usersTable">
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
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info text-white"><i class="fa fa-edit"></i></a>
                                        
                                        <form action="{{ route('admin.users.reset_password', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning text-white" title="Reset Password" onclick="return confirm('Reset password to default?')"><i class="fa fa-key"></i></button>
                                        </form>

                                        @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this user?')"><i class="fa fa-trash"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
