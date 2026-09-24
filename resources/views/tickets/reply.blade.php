<div class="x_panel mt-2">
    <div class="x_title">
        <h2><i class="fa fa-comments"></i> Conversation</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="chat-container d-flex flex-column" style="height: 60vh;">

            <!-- Chat Messages Area -->
            <div id="chat-messages" class="flex-grow-1 p-3 overflow-auto position-relative" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; margin-bottom: 15px; scroll-behavior: smooth;">
                @foreach($messages as $msg)
                @php
                $isMe = $msg->user_id === Auth::id();
                @endphp
                <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-3 message-row" id="msg-{{ $msg->id }}">
                    @if(!$isMe)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->user->name) }}&background=random&size=40" class="rounded-circle me-2" alt="Avatar" style="width: 40px; height: 40px;">
                    @endif

                    <div class="message-bubble p-2 rounded {{ $isMe ? 'bg-primary text-white' : 'bg-white border' }} position-relative" style="max-width: 75%;">

                        <!-- Actions Dropdown -->
                        <div class="dropdown message-actions position-absolute" style="top: 5px; {{ $isMe ? 'left: -30px;' : 'right: -30px;' }}">
                            <a href="#" class="text-secondary" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu shadow-sm" style="min-width: 100px;">
                                <li><a class="dropdown-item" href="#" onclick="replyTo({{ $msg->id }}, '{{ addslashes($msg->user->name) }}', '{{ addslashes(Str::limit($msg->message, 50)) }}')"><i class="fa fa-reply text-muted"></i> Reply</a></li>
                                @if($isMe)
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteMessage({{ $msg->id }})"><i class="fa fa-trash"></i> Delete</a></li>
                                @endif
                            </ul>
                        </div>

                        @if(!$isMe)
                        <div class="fw-bold small mb-1" style="color: #666;">{{ $msg->user->name }}</div>
                        @endif

                        @if($msg->reply_to_id && $msg->replyTo)
                        <div class="reply-preview rounded p-1 mb-2" style="background: rgba(255,255,255,0.2); border-left: 4px solid {{ $isMe ? '#fff' : '#26B99A' }}; font-size: 0.85rem;">
                            <div class="fw-bold">{{ $msg->replyTo->user->name }}</div>
                            <div class="text-truncate">{{ $msg->replyTo->message }}</div>
                        </div>
                        @endif

                        {{-- Attachments --}}
                        @if($msg->attachments && $msg->attachments->count() > 0)
                        <div class="chat-attachments mb-1">
                            @foreach($msg->attachments as $attachment)
                            @if(Str::startsWith($attachment->filetype, 'image/'))
                            <a href="{{ Storage::url($attachment->filepath) }}" target="_blank" class="d-block mb-1">
                                <img src="{{ Storage::url($attachment->filepath) }}" alt="{{ $attachment->filename }}" class="img-fluid rounded" style="max-width: 250px; max-height: 200px; cursor: pointer;">
                            </a>
                            @else
                            <a href="{{ Storage::url($attachment->filepath) }}" target="_blank" class="d-flex align-items-center gap-2 p-2 mb-1 rounded text-decoration-none {{ $isMe ? 'file-attachment-me' : 'file-attachment-other' }}">
                                <i class="fa fa-file" style="font-size: 1.3rem;"></i>
                                <div style="overflow: hidden;">
                                    <div class="text-truncate fw-semibold" style="max-width: 180px; font-size: 0.85rem;">{{ $attachment->filename }}</div>
                                    <div style="font-size: 0.75rem; opacity: 0.7;">{{ strtoupper(pathinfo($attachment->filename, PATHINFO_EXTENSION)) }} file</div>
                                </div>
                                <i class="fa fa-download ms-auto" style="font-size: 0.9rem;"></i>
                            </a>
                            @endif
                            @endforeach
                        </div>
                        @endif

                        @if($msg->message)
                        <div class="message-text" style="word-wrap: break-word; white-space: pre-wrap;">{{ $msg->message }}</div>
                        @endif

                        <div class="text-end small mt-1 {{ $isMe ? 'text-light' : 'text-muted' }}" style="font-size: 0.75rem; display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                            {{ $msg->created_at->format('H:i') }}
                            @if($isMe)
                            <span class="read-receipt" data-msg-id="{{ $msg->id }}" style="color: {{ $msg->is_read ? '#4dc247' : 'inherit' }}; font-size: 0.85rem;">
                                <i class="fa fa-check-double"></i>
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($isMe)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&size=40" class="rounded-circle ms-2" alt="Avatar" style="width: 40px; height: 40px;">
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Replying To Indicator -->
            <div id="replying-to-container" class="d-none bg-light border rounded p-2 mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold small text-primary" id="replying-to-name"></div>
                    <div class="small text-muted text-truncate" id="replying-to-text" style="max-width: 400px;"></div>
                </div>
                <button type="button" class="btn-close" onclick="cancelReply()" aria-label="Close"></button>
            </div>

            <!-- Attachment Preview Bar -->
            <div id="attachment-preview-container" class="d-none bg-light border rounded p-2 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold small text-secondary"><i class="fa fa-paperclip"></i> Attachments</span>
                    <button type="button" class="btn-close btn-sm" onclick="clearAttachments()" aria-label="Close"></button>
                </div>
                <div id="attachment-preview-list" class="d-flex flex-wrap gap-2"></div>
            </div>

            <!-- Chat Input Area -->
            <div class="chat-input-area mt-auto">
                <form id="chat-form" class="d-flex align-items-end gap-2 position-relative">
                    <input type="hidden" id="reply-to-id" value="">

                    <!-- Hidden file input -->
                    <input type="file" id="file-input" multiple class="d-none" accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.csv">

                    <!-- Attachment Button -->
                    <button type="button" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" id="attach-btn" onclick="document.getElementById('file-input').click()" style="width: 40px; height: 40px; padding: 0;" title="Attach files">
                        <i class="fa fa-paperclip" style="font-size: 1.1rem;"></i>
                    </button>

                    <input type="text" id="chat-input" class="form-control" placeholder="Type your message here..." autocomplete="off" style="border-radius: 20px;">

                    <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" id="chat-submit-btn" style="width: 40px; height: 40px; padding: 0;">
                        <i class="fa fa-paper-plane m-0" style="margin-left: -2px !important;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .message-actions {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .message-row:hover .message-actions {
        opacity: 1;
    }

    .message-actions .dropdown-toggle::after {
        display: none;
    }

    .file-attachment-me {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .file-attachment-me:hover {
        background: rgba(255, 255, 255, 0.35);
        color: #fff;
    }

    .file-attachment-other {
        background: #f0f0f0;
        color: #333;
    }

    .file-attachment-other:hover {
        background: #e0e0e0;
        color: #333;
    }

    .attachment-thumb {
        position: relative;
        display: inline-block;
    }

    .attachment-thumb img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #dee2e6;
    }

    .attachment-thumb .file-icon-preview {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        border: 2px solid #dee2e6;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-size: 0.6rem;
        color: #666;
        text-align: center;
        padding: 4px;
        overflow: hidden;
    }

    .attachment-thumb .remove-attachment {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
        border: none;
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    #chat-form .btn-outline-secondary:hover {
        background: #26B99A;
        border-color: #26B99A;
        color: #fff;
    }

</style>

<script>
    const currentUserId = {{ Auth::id() }};
    const currentUserName = "{{ Auth::user()->name }}";
    const ticketId = {{ $ticket->id }};
    const chatContainer = document.getElementById('chat-messages');

    // Set global chat ticket ID so topbar can suppress notifications
    window.currentChatTicketId = ticketId;
    window.addEventListener('beforeunload', function() {
        window.currentChatTicketId = null;
    });

    // Selected files array
    let selectedFiles = [];

    // Scroll to bottom on load
    chatContainer.scrollTop = chatContainer.scrollHeight;

    function escapeHtml(unsafe) {
        return (unsafe || "").replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    // ----- FILE ATTACHMENT HANDLING -----

    document.getElementById('file-input').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire('File Too Large', `"${file.name}" exceeds the 10MB limit.`, 'error');
                return;
            }
            selectedFiles.push(file);
        });
        renderAttachmentPreviews();
        // Reset input so re-selecting the same file works
        e.target.value = '';
    });

    function renderAttachmentPreviews() {
        const container = document.getElementById('attachment-preview-container');
        const list = document.getElementById('attachment-preview-list');
        list.innerHTML = '';

        if (selectedFiles.length === 0) {
            container.classList.add('d-none');
            // Re-enable required on chat-input only if no files
            document.getElementById('chat-input').setAttribute('required', 'required');
            return;
        }

        container.classList.remove('d-none');
        // Remove required from chat-input when files are attached (message becomes optional)
        document.getElementById('chat-input').removeAttribute('required');

        selectedFiles.forEach((file, index) => {
            const thumb = document.createElement('div');
            thumb.className = 'attachment-thumb';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                thumb.appendChild(img);
            } else {
                const ext = file.name.split('.').pop().toUpperCase();
                const iconDiv = document.createElement('div');
                iconDiv.className = 'file-icon-preview';
                iconDiv.innerHTML = `<i class="fa fa-file" style="font-size: 1.2rem; margin-bottom: 2px;"></i><span class="text-truncate" style="max-width: 50px;">${ext}</span>`;
                thumb.appendChild(iconDiv);
            }

            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-attachment';
            removeBtn.innerHTML = '&times;';
            removeBtn.onclick = function() {
                selectedFiles.splice(index, 1);
                renderAttachmentPreviews();
            };
            thumb.appendChild(removeBtn);

            list.appendChild(thumb);
        });
    }

    function clearAttachments() {
        selectedFiles = [];
        renderAttachmentPreviews();
    }

    // ----- REPLY HANDLING -----

    function replyTo(id, userName, messageExcerpt) {
        document.getElementById('reply-to-id').value = id;
        document.getElementById('replying-to-name').innerText = 'Replying to ' + userName;
        document.getElementById('replying-to-text').innerText = messageExcerpt;
        document.getElementById('replying-to-container').classList.remove('d-none');
        document.getElementById('chat-input').focus();
    }

    function cancelReply() {
        document.getElementById('reply-to-id').value = '';
        document.getElementById('replying-to-container').classList.add('d-none');
    }

    // ----- DELETE HANDLING -----

    function deleteMessage(id) {
        Swal.fire({
            title: 'Delete Message?'
            , text: "This will delete the message for everyone."
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#aaa'
            , confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/tickets/${ticketId}/chat/${id}`)
                    .then(res => {
                        const el = document.getElementById(`msg-${id}`);
                        if (el) el.remove();
                    })
                    .catch(err => showError('Failed to delete message.'));
            }
        });
    }

    function clearMyMessages() {
        Swal.fire({
            title: 'Clear My Messages?'
            , text: "This will delete all messages you've sent in this ticket."
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonColor: '#d33'
            , cancelButtonColor: '#aaa'
            , confirmButtonText: 'Clear All'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/tickets/${ticketId}/chat/all`)
                    .then(res => {
                        window.location.reload();
                    })
                    .catch(err => showError('Failed to clear messages.'));
            }
        });
    }

    // ----- RENDER ATTACHMENTS HTML -----

    function renderAttachmentsHtml(attachments, isMe) {
        if (!attachments || attachments.length === 0) return '';

        let html = '<div class="chat-attachments mb-1">';
        attachments.forEach(att => {
            if (att.filetype && att.filetype.startsWith('image/')) {
                html += `<a href="${att.url}" target="_blank" class="d-block mb-1">
                    <img src="${att.url}" alt="${escapeHtml(att.filename)}" class="img-fluid rounded" style="max-width: 250px; max-height: 200px; cursor: pointer;">
                </a>`;
            } else {
                const ext = att.filename ? att.filename.split('.').pop().toUpperCase() : 'FILE';
                const attachClass = isMe ? 'file-attachment-me' : 'file-attachment-other';
                html += `<a href="${att.url}" target="_blank" class="d-flex align-items-center gap-2 p-2 mb-1 rounded text-decoration-none ${attachClass}">
                    <i class="fa fa-file" style="font-size: 1.3rem;"></i>
                    <div style="overflow: hidden;">
                        <div class="text-truncate fw-semibold" style="max-width: 180px; font-size: 0.85rem;">${escapeHtml(att.filename)}</div>
                        <div style="font-size: 0.75rem; opacity: 0.7;">${ext} file</div>
                    </div>
                    <i class="fa fa-download ms-auto" style="font-size: 0.9rem;"></i>
                </a>`;
            }
        });
        html += '</div>';
        return html;
    }

    // ----- APPEND MESSAGE -----

    function appendMessage(message, isMe, userName, timestamp, id = null, isRead = false, replyName = null, replyText = null, attachments = null) {
        const justifyClass = isMe ? 'justify-content-end' : 'justify-content-start';
        const bubbleClass = isMe ? 'bg-primary text-white' : 'bg-white border';
        const timeClass = isMe ? 'text-light' : 'text-muted';
        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=random&size=40`;
        const tempId = id ? `msg-${id}` : `msg-temp-${Date.now()}`;
        const readColor = isRead ? '#4dc247' : 'inherit';

        let html = `<div class="d-flex ${justifyClass} mb-3 message-row" id="${tempId}">`;

        if (!isMe) {
            html += `<img src="${avatarUrl}" class="rounded-circle me-2" alt="Avatar" style="width: 40px; height: 40px;">`;
        }

        html += `<div class="message-bubble p-2 rounded ${bubbleClass} position-relative" style="max-width: 75%;">`;

        // Actions
        if (id) {
            html += `<div class="dropdown message-actions position-absolute" style="top: 5px; ${isMe ? 'left: -30px;' : 'right: -30px;'}">
                <a href="#" class="text-secondary" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu shadow-sm" style="min-width: 100px;">
                    <li><a class="dropdown-item" href="#" onclick="replyTo(${id}, '${escapeHtml(userName)}', '${escapeHtml(message).substring(0,50)}')"><i class="fa fa-reply text-muted"></i> Reply</a></li>
                    ${isMe ? `<li><a class="dropdown-item text-danger" href="#" onclick="deleteMessage(${id})"><i class="fa fa-trash"></i> Delete</a></li>` : ''}
                </ul>
            </div>`;
        }

        if (!isMe) {
            html += `<div class="fw-bold small mb-1" style="color: #666;">${escapeHtml(userName)}</div>`;
        }

        // Reply preview
        if (replyName && replyText) {
            html += `<div class="reply-preview rounded p-1 mb-2" style="background: rgba(255,255,255,0.2); border-left: 4px solid ${isMe ? '#fff' : '#26B99A'}; font-size: 0.85rem;">
                        <div class="fw-bold">${escapeHtml(replyName)}</div>
                        <div class="text-truncate">${escapeHtml(replyText)}</div>
                    </div>`;
        }

        // Attachments
        html += renderAttachmentsHtml(attachments, isMe);

        // Message text
        if (message) {
            html += `<div class="message-text" style="word-wrap: break-word; white-space: pre-wrap;">${escapeHtml(message)}</div>`;
        }

        html += `<div class="text-end small mt-1 ${timeClass}" style="font-size: 0.75rem; display: flex; align-items: center; justify-content: flex-end; gap: 4px;">
                    ${timestamp}`;

        if (isMe) {
            html += `<span class="read-receipt" data-msg-id="${id}" style="color: ${readColor}; font-size: 0.85rem;"><i class="fa fa-check-double"></i></span>`;
        }
        html += `</div></div>`;

        if (isMe) {
            html += `<img src="${avatarUrl}" class="rounded-circle ms-2" alt="Avatar" style="width: 40px; height: 40px;">`;
        }

        html += `</div>`;

        chatContainer.insertAdjacentHTML('beforeend', html);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        return tempId;
    }

    // ----- ECHO LISTENERS -----

    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.private(`ticket.${ticketId}`)
                .listen('.new-message', (e) => {
                    if (e.user_id !== currentUserId) {
                        appendMessage(e.message, false, e.user_name, e.timestamp, e.id, false, e.reply_user_name, e.reply_message, e.attachments || null);

                        // Tell server we read it
                        axios.post(`/tickets/${ticketId}/chat/read`)
                            .catch(err => console.error('Failed to mark read', err));
                    }
                })
                .listen('.messages-read', (e) => {
                    // Update double checkmarks to blue
                    if (e.reader_user_id !== currentUserId) {
                        document.querySelectorAll('.read-receipt').forEach(el => {
                            el.style.color = '#4dc247'; // Whatsapp Blue/Green
                        });
                    }
                })
                .listen('.message-deleted', (e) => {
                    const el = document.getElementById(`msg-${e.message_id}`);
                    if (el) {
                        el.style.transition = 'opacity 0.3s';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 300);
                    }
                });
        }

        // Immediately trigger read status for messages that might have been loaded
        axios.post(`/tickets/${ticketId}/chat/read`);
    });

    // ----- FORM SUBMIT (uses FormData for file uploads) -----

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const input = document.getElementById('chat-input');
        const replyInput = document.getElementById('reply-to-id');

        const message = input.value.trim();
        const replyToId = replyInput.value;
        const replyName = document.getElementById('replying-to-name').innerText.replace('Replying to ', '');
        const replyText = document.getElementById('replying-to-text').innerText;

        const submitBtn = document.getElementById('chat-submit-btn');

        if (!message && selectedFiles.length === 0) return;

        // Build local attachment previews for optimistic UI
        let localAttachments = selectedFiles.map(file => {
            return {
                filename: file.name
                , filetype: file.type
                , url: file.type.startsWith('image/') ? URL.createObjectURL(file) : '#'
            , };
        });

        // Optimistic UI update
        const now = new Date();
        const timestamp = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        const tempId = appendMessage(
            message, true, currentUserName, timestamp, null, false
            , replyToId ? replyName : null
            , replyToId ? replyText : null
            , localAttachments.length > 0 ? localAttachments : null
        );

        // Build FormData
        const formData = new FormData();
        if (message) {
            formData.append('message', message);
        }
        if (replyToId) {
            formData.append('reply_to_id', replyToId);
        }
        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });

        input.value = '';
        cancelReply();
        clearAttachments();
        submitBtn.disabled = true;

        axios.post(`/tickets/${ticketId}/chat`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(res => {
                submitBtn.disabled = false;
                input.focus();
                // Update temp ID with real ID and re-attach data attribute
                const el = document.getElementById(tempId);
                if (el) {
                    el.id = `msg-${res.data.message.id}`;
                    const receipt = el.querySelector('.read-receipt');
                    if (receipt) receipt.dataset.msgId = res.data.message.id;

                    // Add actions dropdown now that we have the ID
                    const actionsHtml = `<div class="dropdown message-actions position-absolute" style="top: 5px; left: -30px;">
                        <a href="#" class="text-secondary" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                        <ul class="dropdown-menu shadow-sm" style="min-width: 100px;">
                            <li><a class="dropdown-item" href="#" onclick="replyTo(${res.data.message.id}, '${escapeHtml(currentUserName)}', '${escapeHtml(message).substring(0,50)}')"><i class="fa fa-reply text-muted"></i> Reply</a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteMessage(${res.data.message.id})"><i class="fa fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>`;
                    el.querySelector('.message-bubble').insertAdjacentHTML('afterbegin', actionsHtml);
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                showError('Failed to send message.');
                const el = document.getElementById(tempId);
                if (el) el.remove();
                console.error(err);
            });
    });

    // Allow Ctrl+V to paste images directly from clipboard
    document.getElementById('chat-input').addEventListener('paste', function(e) {
        const items = (e.clipboardData || e.originalEvent.clipboardData).items;
        for (const item of items) {
            if (item.type.startsWith('image/')) {
                e.preventDefault();
                const file = item.getAsFile();
                if (file) {
                    // Create a named file from clipboard blob
                    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                    const namedFile = new File([file], `pasted-image-${timestamp}.png`, {
                        type: file.type
                    });
                    selectedFiles.push(namedFile);
                    renderAttachmentPreviews();
                }
            }
        }
    });

    // Drag & Drop support
    const chatForm = document.querySelector('.chat-input-area');

    chatForm.addEventListener('dragover', function(e) {
        e.preventDefault();
        chatForm.style.background = 'rgba(38, 185, 154, 0.1)';
        chatForm.style.borderColor = '#26B99A';
    });

    chatForm.addEventListener('dragleave', function(e) {
        chatForm.style.background = '';
        chatForm.style.borderColor = '';
    });

    chatForm.addEventListener('drop', function(e) {
        e.preventDefault();
        chatForm.style.background = '';
        chatForm.style.borderColor = '';

        const files = Array.from(e.dataTransfer.files);
        files.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire('File Too Large', `"${file.name}" exceeds the 10MB limit.`, 'error');
                return;
            }
            selectedFiles.push(file);
        });
        renderAttachmentPreviews();
    });

</script>
