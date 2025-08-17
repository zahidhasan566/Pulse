<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopInformation extends Model
{
    use HasFactory;
    protected $table = "ShopInformation";
    public $primaryKey = 'ShopID';
    protected $guarded = [];
    public $timestamps = false;

    public function competitorShopBusinesses()
    {
        return $this->hasMany(CompetitorShopBusiness::class, 'ShopID', 'ShopID');
    }

    public function businessWithACI()
    {
        return $this->hasMany(BusinessWithACI::class, 'ShopID', 'ShopID');
    }
}
