@extends('layout.induk')

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Edit User: {{ $user->name }}</h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_content">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-horizontal form-label-left">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Full Name <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Email Address <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Roles <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select name="roles[]" class="form-control select2" multiple required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ strtoupper($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group row">
                            <div class="col-md-9 col-sm-9 offset-md-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Update User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
