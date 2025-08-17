<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedVro extends Model
{
    use HasFactory;
    protected $table = "AssignedVro";
    public $primaryKey = 'AssignedVroID';
    protected $guarded = [];
    public $timestamps = false;
}
