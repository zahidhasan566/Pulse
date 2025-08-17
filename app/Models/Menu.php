<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    use HasFactory;
    protected $table = "Menus";
    public $primaryKey = 'MenuID';
    protected $guarded = [];
    public $timestamps = false;
    protected $keyType = 'string';

    public function subMenus() {
        $userId = Auth::user()->RoleID;
        return $this->hasMany(SubMenu::class,'MenuID','MenuID')
            ->join('RolePermissions',function ($q) use ($userId) {
                $q->on('RolePermissions.SubMenuID','SubMenus.SubMenuID')->where('RolePermissions.RoleId',$userId);
            })
            ->where('Status',1)
            ->orderBy('SubMenuOrder');

//        return $this->hasMany(SubMenu::class,'MenuID','MenuID')
//            ->join('SubMenuPermission','SubMenuPermission.SubMenuID','SubMenus.SubMenuID')
//            ->where('SubMenuPermission.UserID',Auth::user()->UserID)->orderBy('SubMenuOrder');
    }

    public function allSubMenus() {
        return $this->hasMany(SubMenu::class,'MenuID','MenuID');
    }
}
