<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//die('hrrr');

class Inventoryremovestocks extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'added_by','prodId','service_name','requantity','reunits','servicereason','recreated_at','updated_at'];
}