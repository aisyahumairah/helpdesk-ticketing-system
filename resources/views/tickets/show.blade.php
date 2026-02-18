@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Ticket Details</h3>
            </div>
            <div class="title_right text-end">
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to
                    List</a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ $ticket->ticket_id }} - {{ $ticket->title }}</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="ticket-info mb-4">
                            <p><strong>Description:</strong></p>
                            <div class="well">
                                {!! $ticket->description !!}
                            </div>
                        </div>

                        @if ($ticket->attachments->count() > 0)
                            <div class="attachments mb-4">
                                <p><strong>Attachments:</strong></p>
                                <ul class="list-unstyled">
                                    @foreach ($ticket->attachments as $file)
                                        <li>
                                            <a href="{{ Storage::url($file->filepath) }}" target="_blank">
                                                <i class="fa fa-paperclip"></i> {{ $file->filename }}
                                            </a>
                                            ({{ number_format(Storage::disk('public')->size($file->filepath) / 1024, 2) }}
                                            KB)
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Threaded Conversation --}}
                <div class="x_panel mt-2">
                    <div class="x_title">
                        <h2>Conversation</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <ul class="list-unstyled msg_list">
                            @foreach ($ticket->replies as $reply)
                                <li class="pb-3 border-bottom mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <strong>{{ $reply->user->name }}</strong>
                                            @if ($reply->user->hasRole('it_support') || $reply->user->hasRole('admin'))
                                                <span class="badge bg-info ms-2">IT Support</span>
                                            @endif
                                        </span>
                                        <span
                                            class="text-muted"><small>{{ $reply->created_at->format('d M Y, H:i') }}</small></span>
                                    </div>
                                    <div class="message mt-2 px-2">
                                        {!! $reply->message !!}
                                    </div>
                                    @if ($reply->attachments->count() > 0)
                                        <div class="reply-attachments mt-2 px-2">
                                            <small class="text-muted d-block mb-1">Attachments:</small>
                                            @foreach ($reply->attachments as $file)
                                                <a href="{{ Storage::url($file->filepath) }}" target="_blank"
                                                    class="mr-3">
                                                    <i class="fa fa-paperclip"></i> <small>{{ $file->filename }}</small>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if (!in_array($ticket->status, ['DONE', 'CLOSE']))
                            <div class="reply-form mt-4">
                                <form action="{{ route('tickets.reply', $ticket) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="form-label">Add a Reply</label>
                                        <textarea name="message" id="reply_message" class="form-control @error('message') is-invalid @enderror" rows="4"
                                            placeholder="Type your message here...">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label">Attachments</label>
                                        <input type="file" name="attachments[]" class="form-control" multiple>
                                        <small class="text-muted">Max 10MB per file. Images, PDFs, Videos allowed.</small>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-reply"></i> Send
                                            Reply</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info text-center mt-4">
                                This ticket is resolved/closed. Further replies are disabled.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Ticket Status</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>Status:</strong>
                                <span
                                    class="badge {{ $ticket->status == 'NEW' ? 'bg-primary' : 'bg-info' }}">{{ $ticket->statusCode->name ?? $ticket->status }}</span>
                            </li>
                            <li class="mb-2"><strong>Urgency:</strong>
                                <span
                                    class="badge {{ $ticket->urgency == 'HIGH' ? 'bg-danger' : 'bg-info' }}">{{ $ticket->urgencyCode->name ?? $ticket->urgency }}</span>
                            </li>
                            <li class="mb-2"><strong>Category:</strong>
                                {{ $ticket->categoryCode->name ?? $ticket->category }}</li>
                            <li class="mb-2"><strong>Created By:</strong> {{ $ticket->user->name }}</li>
                            <li class="mb-2"><strong>Date:</strong> {{ $ticket->created_at->format('d M Y, H:i') }}</li>
                        </ul>

                        @php
                            $isStaff = Auth::user()->hasAnyRole(['admin', 'it_support']);
                            $isAssigned = $ticket->assigned_to === Auth::id();
                            $canAction = Auth::user()->hasRole('admin') || ($isStaff && $isAssigned);
                            $isOwner = $ticket->user_id === Auth::id();
                        @endphp

                        @if ($canAction && !in_array($ticket->status, ['DONE', 'CLOSE']))
                            <div class="ln_solid"></div>
                            <div class="d-flex flex-wrap gap-2">
                                <form id="form-resolve" action="{{ route('tickets.resolve', $ticket) }}" method="POST"
                                    class="flex-grow-1">
                                    @csrf
                                    <button type="button" id="btn-resolve"
                                        class="btn btn-success text-white btn-block w-100">Mark as
                                        Resolved
                                    </button>
                                </form>
                                <form id="form-escalate" action="{{ route('tickets.escalate', $ticket) }}" method="POST"
                                    class="flex-grow-1">
                                    @csrf
                                    <button type="button" id="btn-escalate"
                                        class="btn btn-warning text-dark btn-block w-100">Escalate</button>
                                </form>
                            </div>
                        @endif

                        @if ($isOwner && $ticket->status == 'CLOSED')
                            <div class="ln_solid"></div>
                            <div class="d-flex flex-wrap gap-2">
                                <form id="form-verify" action="{{ route('tickets.verify', $ticket) }}" method="POST"
                                    class="flex-grow-1">
                                    @csrf
                                    <button type="button" id="btn-verify"
                                        class="btn btn-success text-white btn-block w-100">Verify
                                        Resolution</button>
                                </form>
                                <form id="form-reopen" action="{{ route('tickets.reopen', $ticket) }}" method="POST"
                                    class="flex-grow-1">
                                    @csrf
                                    <button type="button" id="btn-reopen"
                                        class="btn btn-danger text-white btn-block w-100">Reopen
                                        Ticket</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Audit Trail</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <ul class="list-unstyled msg_list">
                            @foreach ($ticket->auditTrails as $trail)
                                <li class="pb-2 border-bottom mb-2">
                                    <div>
                                        <strong>{{ $trail->event }}</strong> by {{ $trail->user->name ?? 'System' }}
                                        <span
                                            class="pull-right text-muted"><small>{{ $trail->created_at->format('d M Y, H:i') }}</small></span>
                                    </div>
                                    <div class="message">
                                        @if (is_array($trail->details))
                                            {{ $trail->details['message'] ?? $trail->event }}
                                        @else
                                            {{ $trail->details }}
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize CKEditor for reply if it exists
            if (document.querySelector('#reply_message')) {
                ClassicEditor
                    .create(document.querySelector('#reply_message'))
                    .catch(error => {
                        console.error(error);
                    });
            }

            const supportUsers = @json($supportUsers->map(fn($u) => ['id' => $u->id, 'name' => $u->name]));

            // Resolve Confirmation
            $('#btn-resolve').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Mark as Resolved?',
                    text: "Are you sure you want to mark this ticket as resolved?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#26B99A',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Resolve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-resolve').submit();
                    }
                });
            });

            // Verify Confirmation (User)
            $('#btn-verify').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Verify Resolution?',
                    text: "Are you satisfied with the solution provided?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#26B99A',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Verify!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-verify').submit();
                    }
                });
            });

            // Reopen Confirmation (User)
            $('#btn-reopen').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Reopen Ticket?',
                    text: "Are you sure you want to reopen this ticket? Use this if your issue hasn't been resolved.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d9534f',
                    cancelButtonColor: '#999',
                    confirmButtonText: 'Yes, Reopen!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#form-reopen').submit();
                    }
                });
            });

            // Escalate with Selection
            $('#btn-escalate').on('click', function(e) {
                e.preventDefault();

                let options = {};
                supportUsers.forEach(user => {
                    // Exclude current user from escalation list
                    if (user.id != {{ Auth::id() }}) {
                        options[user.id] = user.name;
                    }
                });

                Swal.fire({
                    title: 'Escalate Ticket',
                    text: 'Select IT Support to refer this ticket to:',
                    input: 'select',
                    inputOptions: options,
                    inputPlaceholder: 'Select IT Support staff...',
                    showCancelButton: true,
                    confirmButtonColor: '#f0ad4e',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Next',
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                resolve();
                            } else {
                                resolve('You need to select a person');
                            }
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const selectedUserId = result.value;

                        Swal.fire({
                            title: 'Add any notes?',
                            input: 'textarea',
                            inputPlaceholder: 'Type explanation for escalation here...',
                            showCancelButton: true,
                            confirmButtonText: 'Escalate Now',
                        }).then((noteResult) => {
                            if (noteResult.isConfirmed) {
                                const notes = noteResult.value || '';

                                // Create hidden inputs and submit
                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'assigned_to',
                                    value: selectedUserId
                                }).appendTo('#form-escalate');

                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'notes',
                                    value: notes
                                }).appendTo('#form-escalate');

                                $('#form-escalate').submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
