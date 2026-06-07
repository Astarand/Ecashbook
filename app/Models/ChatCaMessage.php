<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatCaMessage  extends Model
{
    protected $table = 'chat_ca_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'message',
        'file_name',
        'file_path',
        'is_read'
    ];
}
