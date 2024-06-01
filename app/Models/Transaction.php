<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use HasFactory;
    protected $primaryKey='TransactionId';
    public $timestamps=false;
    public $table='TTransactions';
    protected $fillable=[
        'UserFId',
        'BrancheFId',
        'UserTypeFId',
        'CurrencyFId',
        'FromBranchId',
        'Number',
        'Amount',
        'Note',
        'Response',
        'Sent',
        'DateCreated',
        'DateMovemented'

    ];
    public function user(){
        return $this->belongsTo(User::class,'UserFId');
    }

    public function currency(){
        return $this->belongsTo(Currency::class,'CurrencyFId');
    }
    public function branche(){
        return $this->belongsTo(Branche::class,'BrancheFId');
    }
    public function userType(){
        return $this->belongsTo(UserType::class,'UserTypeFId');
    }
    protected $casts = [
        'send' => 'boolean'
      ];
      
      public function setSentAttribute($value)
      {
        $this->attributes['send'] = $value ? 'oui' : 'non';
      }
}
