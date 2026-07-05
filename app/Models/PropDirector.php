<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropDirector extends Model
{
    use HasFactory;
	protected $table = 'prop_directors'; // explicitly define table
	protected $fillable = [
        'compId','addBy', 'director_name', 'director_phone', 'director_designation', 'director_din', 'director_email','director_signature','created_at','updated_at'
    ];
}
