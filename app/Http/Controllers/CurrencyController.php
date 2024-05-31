<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyCollection;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{

    public function index(){
        
        $currencies = Currency::all();
     
        return new CurrencyCollection($currencies);
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'CurrencyName'=>'required|unique:TCurrencies',
            'CurrencyCode'=>'required|unique:TCurrencies'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=>'Echec d\'enregistrement',
                'errors'=>$validatedData->errors()
            ]); 
        };
        $currency=Currency::create([
            'CurrencyName'=>$request->CurrencyName,
            'CurrencyCode'=>$request->CurrencyCode 
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'Enregistrement reussie',
        ]);
    }
    public function update(Request $request,string $id){
        $request->validate([
            'CurrencyName'=>'required',
            'CurrencyCode'=>'required'
        ]);
        $currency=Currency::find($id);
        $currency->update($request->all());
        return response()->json([
            'status'=>201,
            'message'=>'Modification reussie'
        ]);
    }
    public function destroy(string $id){
        $currency=Currency::find($id);
        $currency->delete();
        return response()->json([
            'status'=>201,
            'message'=>'Suppression reussie'
        ]);
    }
}
