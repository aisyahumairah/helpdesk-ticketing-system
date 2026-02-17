<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
    protected $table = 'ticket_status_history';

    protected $fillable = [
        'ticket_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function oldStatusCode()
    {
        return $this->belongsTo(Code::class, 'old_status', 'code')->where('type', 'ticket_status');
    }

    public function newStatusCode()
    {
        return $this->belongsTo(Code::class, 'new_status', 'code')->where('type', 'ticket_status');
    }
}
