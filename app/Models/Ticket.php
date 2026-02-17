<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'title',
        'description',
        'category',
        'urgency',
        'status',
        'user_id',
        'assigned_to',
        'escalation_level',
        'resolved_at',
        'resolved_by',
        'verified_at',
        'verified_by',
        'reopen_count',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function categoryCode()
    {
        return $this->belongsTo(Code::class, 'category', 'code')->where('type', 'comp_type');
    }

    public function urgencyCode()
    {
        return $this->belongsTo(Code::class, 'urgency', 'code')->where('type', 'urgency');
    }

    public function statusCode()
    {
        return $this->belongsTo(Code::class, 'status', 'code')->where('type', 'ticket_status');
    }

    public function attachments()
    {
        return $this->morphMany(UploadedFile::class, 'fileable');
    }

    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    /**
     * Get the current assignment
     */
    public function currentAssignment()
    {
        return $this->hasOne(TicketAssignment::class)->latest();
    }
}
