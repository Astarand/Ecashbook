<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comp_directors extends Model
{
    use HasFactory;
	protected $fillable = [
        'compId', 'director_name', 'director_phone', 'director_designation', 'director_din', 'director_email','director_signature','created_at','updated_at'
    ];
}
