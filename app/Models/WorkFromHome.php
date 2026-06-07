<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkFromHome extends Model
{
    use HasFactory;

    protected $table = 'work_from_home';

    protected $fillable = [
        'employee_id',
        'emp_id',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'work_plan',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'added_by'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'emp_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForEmployee($query, $empId)
    {
        return $query->where('emp_id', $empId);
    }

    public function scopeForCompany($query, $userId)
    {
        return $query->where('added_by', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedFromDateAttribute()
    {
        return $this->from_date ? $this->from_date->format('Y-m-d') : null;
    }

    public function getFormattedToDateAttribute()
    {
        return $this->to_date ? $this->to_date->format('Y-m-d') : null;
    }

    // Methods
    public function approve($approvedBy)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now()
        ]);
    }

    public function reject($rejectedBy, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy,
            'approved_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function hasOverlapWith($fromDate, $toDate)
    {
        return $this->where('status', '!=', 'rejected')
            ->where(function($query) use ($fromDate, $toDate) {
                $query->whereBetween('from_date', [$fromDate, $toDate])
                    ->orWhereBetween('to_date', [$fromDate, $toDate])
                    ->orWhere(function($q) use ($fromDate, $toDate) {
                        $q->where('from_date', '<=', $fromDate)
                          ->where('to_date', '>=', $toDate);
                    });
            })
            ->exists();
    }
}