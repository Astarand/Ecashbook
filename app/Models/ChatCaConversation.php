<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatCaConversation extends Model
{
    protected $table = 'chat_ca_conversations';

    protected $fillable = [
        'ca_id',
        'company_id',
        'last_message',
        'ca_unread_count',
        'company_unread_count',
        'last_message_at',
        'status'
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }
}
