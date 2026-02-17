@extends('layout.induk')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>View Role</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Role: {{ ucfirst($role->name) }}</h2>
                        <ul class="nav navbar-right panel_toolbox">
                             <li><a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary text-white"><i class="fa fa-arrow-left"></i> Back to List</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <div class="form-horizontal form-label-left">

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">Role Name 
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="name" name="name" disabled class="form-control" value="{{ $role->name }}">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align">Assigned Permissions</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <div class="row">
                                        @php
                                            $groupedPermissions = $role->permissions->groupBy(function($item) {
                                                return explode('.', $item->name)[0];
                                            });
                                        @endphp
                                        
                                        @foreach($groupedPermissions as $module => $permissions)
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <strong>{{ ucfirst($module) }}</strong>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($permissions as $permission)
                                                    <span class="badge badge-success mb-1">{{ $permission->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ln_solid"></div>
                             <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">Edit Role</a>
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
