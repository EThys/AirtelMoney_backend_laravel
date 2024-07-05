<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhoneTypes;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionCollection;
use App\Models\Branche;
use App\Models\Currency;
use App\Models\UserType;

class TransactionController extends Controller
{

    public function index(){
        $transactions=Transaction::all();
        return response()->json($transactions);
        // $usersType = Transaction::whereHas('userType', function($q) {
        //     $q->where('UserTypeId', 2);
        //   })->get();
        //   return response()->json($usersType);
    }

    public function transactionByCurrency(Request $request,$currencyCode, $perPage = 20) {

        $userId = auth()->user()->UserId;
        
        $admin = auth()->user()->Admin;
        $from = $request->from;
        $to = $request->to;
    
        if($admin == 0){
            $transactions = Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
          ->where('TCurrencies.CurrencyCode', $currencyCode)
          ->with('userType','currency','user',"branche")
          ->when($from && $to, function($query) use ($from, $to) {
            $query->whereRaw("DateMovemented >= ? AND DateMovemented <= ?", array($from, $to));
          })

          ->orderBy('TransactionId', 'desc');
        }
        else{
            $transactions = Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
        ->where('TCurrencies.CurrencyCode', $currencyCode)
        ->where('TTransactions.UserFId', $userId)
        ->with('userType', 'currency', 'user', "branche")
        ->when($from && $to, function($query) use ($from, $to) {
            $query->whereRaw("DateMovemented >= ? AND DateMovemented <= ?", array($from, $to));
          })
        ->orderBy('TransactionId', 'desc');
        }
      
        // Add pagination
        $paginated = $transactions->paginate($perPage);
      
        return new TransactionCollection(
          $paginated
        );
      
      }
        //  $userId = auth()->user()->id;

        //  return new TransactionCollection(
        //    Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
        //      ->where('TCurrencies.CurrencyCode', $currencyCode)
        //      ->where('TTransactions.UserFId', $userId) 
        //      ->with('userType','currency','user',"branche")
        //      ->orderBy('TransactionId', 'desc')
        //      ->get()
        //     );
          
        
            // return Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
        //              ->where('TCurrencies.CurrencyCode', $currencyCode)->get();             
    

    public function store(Request $request){
        
        function validateNumberFormat($number) {
            // Vérifie les premiers caractères du numéro
            $validateNumber = substr($number, 0, 3);
        
            // Vérifie si le numéro commence par '099' ou '097'
            if (in_array($validateNumber, ['099', '097','098'])) {
                return true;
            }
        
            // Vérifie à nouveau les premiers caractères mais seulement les deux premiers cette fois-ci
            $validateNumber = substr($number, 0, 2);
        
            // Vérifie si le numéro commence par '99' ou '97'
            if (in_array($validateNumber, ['99', '97','98'])) {
                return true;
            }
        
            // Si aucune des conditions précédentes n'est remplie, retourne false
            return false;
        }
        $data = [];
        $validatedData=Validator::make($request->all(),
        [
            
            'CurrencyFId'=>['required', 'exists:TCurrencies,CurrencyId'],
            'Number'=>'required',
            'BrancheFId'=>'required',
            'UserTypeFId'=>'required',
            'Amount'=>'required|integer',
            'Note'=>'nullable|string',
            'Response'=>'nullable|string'
            
        ]);
       
        if($validatedData->fails()){
            return response()->json(
                [
                    'status'=>400,
                    'message'=>'fill in the required fields',
                    'errors'=>$validatedData->errors()
                ]);
        }
        if(!validateNumberFormat($request->Number)) {
           return response()->json(['error' => 'The number entered is not an airtel number']);
        }
        if(strlen($request->Number) !== 10 && strlen($request->Number) !== 9){
            return response()->json(['error' => 'Enter a valid number']);
        }
         
        $userConnected=auth()->user()->UserId;
        $user=User::find($userConnected);
        $brancheId=$user->branche->BrancheId;
        $userTypeId=$user->userType->UserTypeId;
        $dateMovemented = now();
        $dateMovemented = $dateMovemented->format('Y-m-d');
        $phoneNumber=$request->Number;
        $phoneType = PhoneTypes::where('PhoneNumber', $phoneNumber)->first();
        $userType="";
        if($phoneType){
            $userType = $phoneType->userType->UserTypeName;
        }
        
       $transa = Transaction::create([
            'UserFId' => $userConnected,
            'BrancheFId' => $request->BrancheFId,
            'UserTypeFId'=>$request->UserTypeFId,
            'CurrencyFId'=>$request->CurrencyFId,
            'FromBranchId'=>$request->BrancheFId,
            'Number'=>$request->Number,
            'Amount'=>$request->Amount,
            'Note'=>$request->Note,
            'DateMovemented'=>$dateMovemented,
        ]);

        $branch = Branche::find($transa->BrancheFId)->first();
        $currency = Currency::find($transa->CurrencyFId)->first();
        $user_type = UserType::find($transa->UserTypeFId)->first();
        $transa["branche"] =$branch;
        $transa["user_type"] =$user_type;
        $transa["currency"] =$currency;
         $transa;
        return response()->json([
            'status'=>200,
            'message'=>"Request sent",
            "transactions"=>$transa
        ],200);
        
    }
    public function show(strinG $id){
        $transaction=Transaction::find($id);
        return response()->json($transaction);
        // $user=$transaction->user;
        
    }
    public function update(Request $request, string $id){

        function validatedNumberFormat($number) {
            // Vérifie les premiers caractères du numéro
            $validateNumber = substr($number, 0, 3);
        
            // Vérifie si le numéro commence par '099' ou '097'
            if (in_array($validateNumber, ['099', '097','098'])) {
                return true;
            }
        
            // Vérifie à nouveau les premiers caractères mais seulement les deux premiers cette fois-ci
            $validateNumber = substr($number, 0, 2);
        
            // Vérifie si le numéro commence par '99' ou '97'
            if (in_array($validateNumber, ['99', '97','98'])) {
                return true;
            }
        
            // Si aucune des conditions précédentes n'est remplie, retourne false
            return false;
        }
        $transaction=Transaction::find($id);

        if(!validatedNumberFormat($request->Number)) {
            return response()->json(['error' => 'The number entered is not an airtel number']);
        }
        if(strlen($request->Number) !== 10){
             return response()->json(['error' => 'Enter a valid number']);
        }

        $transaction->update($request->all());
        return response()->json([
            'status'=>201,
            'message'=>'Edit successful'
        ]);

    }
    public function destroy(string $id){
        $transaction=Transaction::find($id);
        $transaction->delete();
        return response()->json(['message'=>"Delete successful"],200);
    }

}
