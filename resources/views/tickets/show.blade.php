@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Ticket Details</h3>
            </div>
            <div class="title_right text-end">
                {{-- <a href="{{ route('tickets.chat', $ticket) }}" class="btn btn-primary">
                    <i class="fa fa-comments"></i> Chat
                    @php
                        $unread = \App\Models\ChatMessage::where('ticket_id', $ticket->id)
                            ->where('user_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if($unread > 0)
                        <span class="badge bg-danger ms-1">{{ $unread }}</span>
                    @endif
                </a> --}}
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">
                @include('tickets.ticketdetails')

                {{-- Threaded Conversation --}}
                @include('tickets.reply')
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                @include('tickets.ticketstatus')

                @include('tickets.ticket-audit')
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
        });
    </script>
@endsection
