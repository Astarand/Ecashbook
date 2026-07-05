<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//die('hrrr');

class Inventorystocks extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'added_by','prodId','prod_name','quantity','units','reason','created_at','updated_at'];
}