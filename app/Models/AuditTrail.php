<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'ticket_id',
        'event',
        'details',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
