@extends('layout.induk')

@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Ticket Details</h3>
            </div>
            <div class="title_right text-end">
                <a href="{{ route('support.tickets') }}" class="btn btn-secondary">
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
                @include('tickets.replies')
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
