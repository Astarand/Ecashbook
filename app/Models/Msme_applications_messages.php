<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msme_applications_messages extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_utype',
        'message',
        'attachment'
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTickets::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
