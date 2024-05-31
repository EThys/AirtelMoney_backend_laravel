<?php

namespace App\Http\Controllers;

use App\Models\Branche;
use Illuminate\Http\Request;
use App\Http\Resources\BrancheCollection;
use Illuminate\Support\Facades\Validator;

class BrancheController extends Controller
{
    public function index(){
        $branches = Branche::all();
        
        return new BrancheCollection($branches);
    }

    public function store(Request $request){
        $validatedData=Validator::make($request->all(),[
            'BrancheName'=>'required|unique:TBranches'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>401,
                'message'=>'Echec d\'enregistrement',
                'errors'=>$validatedData->errors()
            ]); 
        };
        
        $branche=Branche::create([
            'BrancheName'=>$request->BrancheName
        ]);
        return response()->json([
            'status'=>201,
            'message'=>'Enregistrement reussie',
        ]);
    }
    public function show(string $id){
        $branche = Branche::with('users.userType')->find($id);
        $user=$branche->users;
        $userserType ="";
        foreach($branche->users as $user) {
            // afficher le type
            echo $userserType=$user->type->UserTypeName;
          }
        
        return response()->json(
            [
                dd($userserType)
            ]
            );
    }
    public function update(Request $request,string $id){
        $request->validate([
            'BrancheName'=>'required'
        ]);
        $branche=Branche::find($id);
        $branche->update($request->all());
        return response()->json([
            'status'=>201,
            'message'=>'Modification reussie'
        ]);
    }
    public function destroy(string $id){
        $branche=Branche::find($id);
        $branche->delete();
        return response()->json([
            'status'=>201,
            'message'=>'Suppression reussie'
        ]);
    }
    
}
