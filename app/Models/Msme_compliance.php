<?php
// app/Models/MsmeApplication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msme_compliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'mobile_no',
        'email',
        'udyam_registration',
        'preferred_service',
        'additional_details',
        'created_at',
        'updated_at'
    ];
}
