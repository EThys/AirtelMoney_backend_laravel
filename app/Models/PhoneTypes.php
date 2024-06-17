<?php

namespace App\Models;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneTypes extends Model
{
    use HasFactory;
    protected $primaryKey='PhoneTypeId';
    public $timestamps=false;
    public $table='TPhoneTypes';
    protected $fillable=[
        'PhoneNumber',
        'UserTypeFId',
        'Note'
    ];

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'UserTypeFId', 'UserTypeId');
    }

}
