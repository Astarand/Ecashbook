<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compliance_reminder extends Model
{
    use HasFactory;

    protected $table = 'compliancereminderset'; // if your table name is `tds_tax_slab`
}