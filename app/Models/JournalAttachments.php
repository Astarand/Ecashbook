<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalAttachments extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'journals_id',
        'file_path'
    ];

    public function journal()
    {
        return $this->belongsTo(Journals::class);
    }
}
