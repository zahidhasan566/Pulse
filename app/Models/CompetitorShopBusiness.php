<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorShopBusiness extends Model
{
    use HasFactory;
    protected $table = "CompetitorShopBusiness";
    public $primaryKey = 'CompetitorID';
    protected $guarded = [];
    public $timestamps = false;
}
