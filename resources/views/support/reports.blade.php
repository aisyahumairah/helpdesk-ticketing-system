@extends('layout.induk')

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Ticket Reports</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Generate PDF Report</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ route('support.reports') }}" method="GET" target="_blank">
                        <input type="hidden" name="export" value="pdf">
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-control select2">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->code }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control select2">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->code }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ln_solid"></div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf"></i> Generate PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
