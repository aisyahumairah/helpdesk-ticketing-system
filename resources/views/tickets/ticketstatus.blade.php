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
            $isAssigned = $ticket->assigned_to === Auth::id();
            $isOwner = $ticket->user_id === Auth::id();
        @endphp

        @if (!in_array($ticket->status, ['DONE', 'CLOSE']))
            <div class="ln_solid"></div>
            <div class="d-flex flex-wrap gap-2">
                @if ($isAssigned && Auth::user()->can('ticket.resolve'))
                    <form id="form-resolve" action="{{ route('tickets.resolve', $ticket) }}" method="POST"
                        class="flex-grow-1">
                        @csrf
                        <button type="button" id="btn-resolve" class="btn btn-success text-white btn-block w-100">Mark
                            as
                            Resolved
                        </button>
                    </form>
                @endif
                @if ($isAssigned && Auth::user()->can('ticket.escalate'))
                    <form id="form-escalate" action="{{ route('tickets.escalate', $ticket) }}" method="POST"
                        class="flex-grow-1">
                        @csrf
                        <button type="button" id="btn-escalate"
                            class="btn btn-warning text-dark btn-block w-100">Escalate</button>
                    </form>
                @endif
            </div>
        @endif

        @if ($isOwner && $ticket->status == 'CLOSE')
            <div class="ln_solid"></div>
            <div class="d-flex flex-wrap gap-2">
                <form id="form-verify" action="{{ route('tickets.verify', $ticket) }}" method="POST"
                    class="flex-grow-1">
                    @csrf
                    <button type="button" id="btn-verify" class="btn btn-success text-white btn-block w-100">Verify
                        Resolution</button>
                </form>
                <form id="form-reopen" action="{{ route('tickets.reopen', $ticket) }}" method="POST"
                    class="flex-grow-1">
                    @csrf
                    <button type="button" id="btn-reopen" class="btn btn-danger text-white btn-block w-100">Reopen
                        Ticket</button>
                </form>
            </div>
        @endif
    </div>
</div>
