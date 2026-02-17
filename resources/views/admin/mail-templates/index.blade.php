@extends('layout.induk')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Mail Templates Management</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Templates List</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                @can('mail_template.create')
                                    <li><a href="{{ route('admin.mail_templates.create') }}"
                                            class="btn btn-sm btn-primary text-white"><i class="fa fa-plus"></i> Create New
                                            Template</a></li>
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
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>Updated At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($templates as $template)
                                                    <tr>
                                                        <td>{{ $template->id }}</td>
                                                        <td>{{ $template->name }}</td>
                                                        <td>{{ $template->subject }}</td>
                                                        <td>
                                                            @if ($template->is_active)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $template->updated_at->format('Y-m-d H:i') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.mail_templates.show', $template->id) }}"
                                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                            @can('mail_template.update')
                                                                <a href="{{ route('admin.mail_templates.edit', $template->id) }}"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="fa fa-pencil"></i></a>
                                                            @endcan
                                                            @can('mail_template.delete')
                                                                <form
                                                                    action="{{ route('admin.mail_templates.destroy', $template->id) }}"
                                                                    method="POST" style="display:inline-block;"
                                                                    class="delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm delete-btn"><i
                                                                            class="fa fa-trash"></i></button>
                                                                </form>
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
                window.confirmAction('Are you sure you want to delete this template?', function() {
                    form.submit();
                });
            });
        });
    </script>
@endsection
