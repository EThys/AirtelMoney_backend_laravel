<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;
    public $table='TCurrencies';
    public $timestamps=false;
    protected $primaryKey='CurrencyId';
    protected $fillable=[
        'CurrencyId',
        'CurrencyName',
        'CurrencyCode'
    ];

    public function transactions(){
        return $this->hasMany(Transaction::class,'CurrencyFId');
    }
}
