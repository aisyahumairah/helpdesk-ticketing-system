<div class="x_panel">
    <div class="x_title">
        <h2>Audit Trail</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <ul class="list-unstyled msg_list">
            @foreach ($timeline as $item)
                <li class="pb-2 border-bottom mb-2">
                    <div>
                        @if($item->type === 'chat')
                            <strong><i class="fa fa-comment text-primary me-1"></i> {{ $item->event }}</strong> by {{ $item->user_name }}
                        @else
                            <strong><i class="fa fa-history text-secondary me-1"></i> {{ $item->event }}</strong> by {{ $item->user_name }}
                        @endif
                        <span class="pull-right text-muted"><small>{{ $item->created_at->format('d M Y, H:i') }}</small></span>
                    </div>
                    <div class="message {{ $item->type === 'chat' ? 'text-primary fst-italic mt-1' : 'mt-1' }}" style="font-size: 0.9em;">
                        {{ $item->details }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
