<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserTypeCollection;

class UserTypeController extends Controller
{

    public function index()
    {
        $userTypes = UserType::all();        
        return new UserTypeCollection($userTypes);
    }
    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'UserTypeName'=>'required|unique:TUserTypes'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=>'Echec d\'enregistrement',
                'errors'=>$validatedData->errors()
            ]); 
        };
        $type=UserType::create([
            'UserTypeName'=>$request->UserTypeName
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'Enregistrement reussie',
        ]);
    }

    public function update(Request $request,string $id){
        $request->validate([
            'UserTypeName'=>'required'
        ]);
        $userType=UserType::find($id);
        $userType->update($request->all());
        return response()->json([
            'status'=>201,
            'message'=>'Modification reussie'
        ]);
    }
    public function destroy(string $id){
        $userType=UserType::find($id);
        $userType->delete();
        return response()->json([
            'status'=>201,
            'message'=>'Suppression reussie'
        ]);
    }
}
