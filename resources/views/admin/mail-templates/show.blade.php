@extends('layout.induk')

@section('content')
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>View Mail Template</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ $mailTemplate->name }}</h2>
                        <ul class="nav navbar-right panel_toolbox">
                             <li><a href="{{ route('admin.mail_templates.index') }}" class="btn btn-sm btn-secondary text-white"><i class="fa fa-arrow-left"></i> Back to List</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <table class="table table-striped">
                                    <tr>
                                        <th style="width: 150px">Subject</th>
                                        <td>{{ $mailTemplate->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td><code>{{ $mailTemplate->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($mailTemplate->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $mailTemplate->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Available Placeholders</th>
                                        <td>
                                            @if($mailTemplate->available_placeholders)
                                                @foreach(($mailTemplate->available_placeholders) as $placeholder)
                                                    <span class="badge badge-info">{{ $placeholder }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                
                                <div class="mt-4">
                                    <h4>Text Body Preview</h4>
                                    <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px;">{{ $mailTemplate->body_text ?? 'No text body available.' }}</pre>
                                </div>
                            </div>
                            
                            <div class="col-md-6 col-sm-12">
                                <h4>HTML Body Preview</h4>
                                <div style="border: 1px solid #ddd; height: 500px; border-radius: 4px; overflow: hidden;">
                                    <iframe srcdoc="{{ $mailTemplate->body_html }}" style="width: 100%; height: 100%; border: none;"></iframe>
                                </div>
                            </div>
                        </div>

                         <div class="ln_solid"></div>
                         <div class="item form-group">
                            <div class="col-md-6 col-sm-6">
                                <a href="{{ route('admin.mail_templates.edit', $mailTemplate->id) }}" class="btn btn-primary">Edit Template</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
