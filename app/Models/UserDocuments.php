<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocuments extends Model
{
    use HasFactory;
	protected $fillable = [
        'user_id',
        'proprietorship_id',
        'document_type',
        'file_type',
        'document_name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
	
}