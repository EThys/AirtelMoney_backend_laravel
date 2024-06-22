<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthCollection;
use App\Models\User;
use function Ramsey\Uuid\v1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function allUsers(){
        $users = User::all();
        
        return new AuthCollection($users);
    }
    public function login(Request $request){
        $validatedData=Validator::make($request->all(),
        [
            'UserName'=>'required',
            'Password'=>'required'

        ]);
        if($validatedData->fails()){
            return response()->json([
              'status'=>400,
              'errors'=>$validatedData->errors()
            ],400);
        }
        $user = User::with('branche') 
                   ->where('UserName', $request->UserName)
                   ->first();

        if(!$user){
            return response()->json([
              'message' => 'unknown user' 
            ],400);
        }
        if(!Hash::check($request->Password, $user->Password)){
            return response()->json([
              'message' => 'Incorrect password'
            ],400);
          }
          

        return response()->json([
            'status' => 200,
            'message' => 'Connexion réussie',
            'token'=>$user->createToken("API TOKEN")->plainTextToken,
            'user'=>$user
        ],200);
        
    }

    public function register(Request $request){
        $validatedData=Validator::make($request->all(),
        [
            "BrancheFId"=>'required',
            "UserTypeFId"=>'required',
            "UserName"=>'required|unique:TUsers',
            "Password"=>'required',
            "Admin"=>'required'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>400,
                'message'=>'Inscription echouée',
                'errors'=>$validatedData->errors()
            ],400);
        }

        return response()->json([
            'status'=>200,
            'message'=>$request->input('Admin')
],200);

        $user=User::create([
            'BrancheFId'=>$request->BrancheFId,
            'UserTypeFId'=>$request->UserTypeFId,
            'UserName'=>$request->UserName,
            'Password'=>bcrypt($request->Password),
            'Admin'=>$request->Admin

        ]);
        return response()->json([
            'status'=>200,
            'message'=>'Inscription reussie',
            'token'=>$user->createToken("API TOKEN")->plainTextToken
        ],200);
    }

    public function profile(){
        $userConnect=auth()->user();
        return response()->json([
            'status' => 200,
            'message' => 'Information de profile',
            'data'=>$userConnect,
            'id'=>$userConnect->id
          ],200);
       }
    
    public function logout() {
    
        auth()->user()->tokens()->delete();
      
        return response()->json([
          'status' => 200,
          'message' => 'Deconnecté'
        ],200);
    }
}
