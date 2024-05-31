<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branche extends Model
{
    use HasFactory;
    protected $primaryKey='BrancheId';
    public $timestamps=false;
    public $table='TBranches';
    protected $fillable=[
        'BrancheName'
    ];

    public function users(){
        return $this->hasMany(User::class, 'BrancheFId'); 
    }
    public function transactions(){
        return $this->hasMany(Transaction::class,'BrancheFId');
    }
}
