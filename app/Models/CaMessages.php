<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaMessages extends Model
{
    use HasFactory;
	protected $table = 'ca_messages';
    protected $fillable = ['ca_id', 'admin_id', 'subject', 'message', 'attachment'];

    
}