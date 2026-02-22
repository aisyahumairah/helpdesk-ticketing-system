@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User Profile</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Profile Information</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" class="form-horizontal form-label-left">
                            @csrf

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">Name <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" id="name" name="name" required="required"
                                        class="form-control" value="{{ old('name', $user->name) }}">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="email">Email</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="email" id="email" class="form-control" value="{{ $user->email }}"
                                        disabled>
                                    <small class="text-muted">Email cannot be changed.</small>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="phone">Phone
                                    Number</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>

                            @if (!empty($user->roles->first()))
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="phone">
                                        Role
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <span class="badge bg-info">{{ $user->roles->first()->name }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="ln_solid"></div>



                            <div class="ln_solid"></div>
                            <div class="item form-group text-center">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
