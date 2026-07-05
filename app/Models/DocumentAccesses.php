<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAccesses extends Model
{
    use HasFactory;
	protected $fillable = [
        'document_id',
        'granted_to',
        'granted_by',
        'documentType',
		'doc_permission'
    ];

    
}