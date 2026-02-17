<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    protected $fillable = [
        'ticket_id',
        'assigned_from',
        'assigned_to',
        'notes',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function assignedFrom()
    {
        return $this->belongsTo(User::class, 'assigned_from');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
