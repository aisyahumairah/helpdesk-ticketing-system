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
