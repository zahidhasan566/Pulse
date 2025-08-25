<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDetails extends Model
{
    use HasFactory;
    protected $table = "PackageDetails";
    public $primaryKey = 'PackageDetailsID';
    protected $guarded = [];
    public $timestamps = false;
}
