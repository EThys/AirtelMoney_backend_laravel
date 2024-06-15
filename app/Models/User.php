<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Branche;
use App\Models\UserType;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,  HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey='UserId';
    public $timestamps=false;
    public $table='TUsers';
    protected $fillable = [
        'UserName',
        'Password',
        'BrancheFId',
        'UserTypeFId',
        'Admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    public function branche(){
        return $this->belongsTo(Branche::class,'BrancheFId');
    }
    public function userType(){
        return $this->belongsTo(UserType::class,'UserTypeFId');
    }
    public function transactions(){
        return $this->hasMany(UserType::class,'TransactionId');
    }


    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Password' => 'hashed',
        ];
    }
}
