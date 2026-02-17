@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Create New Ticket</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Ticket Details</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data"
                            class="form-horizontal form-label-left">
                            @csrf

                            @if ($users)
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="user_id">Requestor
                                        (User) <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="user_id" id="user_id" class="form-control select2" required>
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                                    ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="title">Title <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" id="title" name="title" required="required"
                                        class="form-control" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="category">Category <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select name="category" id="category" class="form-control select2" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->code }}"
                                                {{ old('category') == $category->code ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="urgency">Urgency <span
                                        class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <select name="urgency" id="urgency" class="form-control select2" required>
                                        <option value="">Select Urgency</option>
                                        @foreach ($urgencies as $urgency)
                                            <option value="{{ $urgency->code }}"
                                                {{ old('urgency') == $urgency->code ? 'selected' : '' }}>
                                                {{ $urgency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="description">Description
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea id="description" name="description" required="required" class="form-control" rows="5">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align"
                                    for="attachments">Attachments</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="file" id="attachments" name="attachments[]" class="form-control"
                                        multiple>
                                    <small class="text-muted">You can upload multiple files (Images, PDF, Video). Max size:
                                        10MB per file.</small>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success">Submit Ticket</button>
                                    <a href="{{ route('tickets.index') }}" class="btn btn-primary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
