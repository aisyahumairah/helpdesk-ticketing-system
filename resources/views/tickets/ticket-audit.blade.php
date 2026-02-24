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
