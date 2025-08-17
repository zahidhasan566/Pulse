<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table = "Business";
    public $primaryKey = 'BusinessID';
    protected $guarded = [];
    public $timestamps = false;
}
