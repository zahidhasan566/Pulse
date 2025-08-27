<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
    use HasFactory;
    protected $table = "Banks";
    public $primaryKey = 'BankID';
    protected $guarded = [];
    public $timestamps = false;
}
