<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserType extends Model
{
    use HasFactory;
    protected $primaryKey = "UserTypeId";
    public $timestamps = false;
    public $table='TUserTypes';
    protected $fillable=[
        'UserTypeName'
    ];

    public function users() {
        return $this->hasMany(User::class,'UserTypeFId');
    }
    public function transactions() {
        return $this->hasMany(Transaction::class,'UserTypeFId');
    }
}
