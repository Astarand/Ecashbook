<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset_series extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'series_name','created_at','updated_at'];
}
