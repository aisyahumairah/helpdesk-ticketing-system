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
                        <span class="text-muted"><small>{{ $reply->created_at->format('d M Y, H:i') }}</small></span>
                    </div>
                    <div class="message mt-2 px-2">
                        {!! $reply->message !!}
                    </div>
                    @if ($reply->attachments->count() > 0)
                        <div class="reply-attachments mt-2 px-2">
                            <small class="text-muted d-block mb-1">Attachments:</small>
                            @foreach ($reply->attachments as $file)
                                <a href="{{ Storage::url($file->filepath) }}" target="_blank" class="mr-3">
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
                <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
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
