<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = \App\Models\Ticket::find($ticketId);
    if (!$ticket) return false;

    // Allow if admin or IT support
    if ($user->hasRole('admin') || $user->hasRole('it_support')) {
        return true;
    }

    // Allow if ticket owner
    return (int) $user->id === (int) $ticket->user_id;
});
