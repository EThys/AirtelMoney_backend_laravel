<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhoneTypeCollection;
use App\Models\PhoneTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneTypeController extends Controller
{

    public function index(){
        // Utilise 'with' pour charger les relations
        $phoneTypes = PhoneTypes::with('userType')->get();
        
        // Retourne les données avec la collection appropriée
        return new PhoneTypeCollection($phoneTypes);
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'PhoneNumber'=>'required|unique:TPhoneTypes',
            'UserTypeFId'=>'required'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=>'Echec d\'enregistrement',
                'errors'=>$validatedData->errors()
            ]); 
        };
        $phoneType=PhoneTypes::create([
            'PhoneNumber'=>$request->PhoneNumber,
            'UserTypeFId'=>$request->UserTypeFId
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'Enregistrement reussie',
        ]);

    }
    public function update(Request $request, string $id){

    }
    public function delete(string $id){

    }

}
