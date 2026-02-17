@extends('layout.induk')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Edit Mail Template</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit Template: {{ $mailTemplate->name }}</h2>
                        <ul class="nav navbar-right panel_toolbox">
                             <li><a href="{{ route('admin.mail_templates.index') }}" class="btn btn-sm btn-secondary text-white"><i class="fa fa-arrow-left"></i> Back to List</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form action="{{ route('admin.mail_templates.update', $mailTemplate->id) }}" method="POST" class="form-horizontal form-label-left">
                            @csrf
                            @method('PUT')

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="name">Template Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="name" name="name" required="required" class="form-control " value="{{ old('name', $mailTemplate->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="subject">Subject <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" id="subject" name="subject" required="required" class="form-control " value="{{ old('subject', $mailTemplate->subject) }}">
                                    @error('subject')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="body_html">HTML Body <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea id="body_html" name="body_html" required="required" class="form-control" rows="10">{{ old('body_html', $mailTemplate->body_html) }}</textarea>
                                    @error('body_html')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Use {{ placeholder_name }} for dynamic content.</small>
                                </div>
                            </div>
                            
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="body_text">Text Body
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea id="body_text" name="body_text" class="form-control" rows="5">{{ old('body_text', $mailTemplate->body_text) }}</textarea>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="available_placeholders">Available Placeholders
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <select id="available_placeholders" name="available_placeholders[]" class="form-control select2" multiple="multiple">
                                        @php
                                            $placeholders = $mailTemplate->available_placeholders ?? [];
                                            // Handle case where stored value might be a string in DB but not cast in an older Eloquent model, or just to be safe
                                            if (is_string($placeholders)) {
                                                $placeholders = json_decode($placeholders, true);
                                            }
                                        @endphp
                                        @foreach($placeholders as $placeholder)
                                            <option value="{{ $placeholder }}" selected>{{ $placeholder }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Type and press enter to add placeholders.</small>
                                </div>
                            </div>
                            
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="is_active">Status
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <select id="is_active" name="is_active" class="form-control select2">
                                        <option value="1" {{ old('is_active', $mailTemplate->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $mailTemplate->is_active) ? '' : 'selected' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="item form-group">
                                <div class="col-md-6 col-sm-6 offset-md-3">
                                    <button type="submit" class="btn btn-success">Update Template</button>
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

@section('script')
<script>
    $(document).ready(function() {
        $('#available_placeholders').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            theme: 'bootstrap-5',
            placeholder: "Add placeholders..."
        });
        
        $('#is_active').select2({
            theme: 'bootstrap-5',
            minimumResultsForSearch: Infinity
        });
    });
</script>
@endsection
