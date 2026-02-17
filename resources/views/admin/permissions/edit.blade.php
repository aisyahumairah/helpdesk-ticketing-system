@extends('layout.induk')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Edit Permission</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Permission: {{ $permission->name }}</h2>
                        <ul class="nav navbar-right panel_toolbox">
                             <li><a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary text-white"><i class="fa fa-arrow-left"></i> Back to List</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="form-horizontal form-label-left">
                            @csrf
                            @method('PUT')

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">Permission Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="name" name="name" required="required" class="form-control " value="{{ old('name', $permission->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format: module.action (e.g. ticket.create, user.update)</small>
                                </div>
                            </div>
                            
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="guard_name">Guard Name
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="guard_name" name="guard_name" class="form-control" value="{{ $permission->guard_name }}" readonly>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success">Update Permission</button>
                                    <button class="btn btn-primary" type="reset">Reset</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
