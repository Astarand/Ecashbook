<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTickets extends Model
{
    use HasFactory;
	protected $fillable = [
        'ticket_no',
        'user_id',
        'query_type',
        'other_query',
        'status'
    ];

    public function messages()
    {
        return $this->hasMany(SupportTicketMessages::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}