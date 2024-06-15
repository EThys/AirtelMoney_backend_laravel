<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionCollection;

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

    public function transactionByCurrency($currencyCode, $perPage = 20) {

        $userId = auth()->user()->UserId;
        
        $admin = auth()->user()->Admin;
    
        if($admin == 0){
            $transactions = Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
          ->where('TCurrencies.CurrencyCode', $currencyCode)
          ->with('userType','currency','user',"branche")
          ->orderBy('TransactionId', 'desc');
        }else{
            $transactions = Transaction::join('TCurrencies', 'TTransactions.CurrencyFId', '=', 'CurrencyId')
        ->where('TCurrencies.CurrencyCode', $currencyCode)
        ->where('TTransactions.UserFId', $userId)
        ->with('userType', 'currency', 'user', "branche")
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
            $validateNumber = substr($number, 0, 3);
            return in_array($validateNumber, ['099', '097']);
        }
        

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
                    'message'=>'Une erreur est survenue',
                    'errors'=>$validatedData->errors()
                ]);
        }
        if(!validateNumberFormat($request->Number)) {
           return response()->json(['error' => 'Le numero saisi n\'est pas un numero airtel']);
        }
        if(strlen($request->Number) !== 10){
            return response()->json(['error' => 'Entrer un numero valide']);
        }
         
        $userConnected=auth()->user()->UserId;
        $user=User::find($userConnected);
        $brancheId=$user->branche->BrancheId;
        $userTypeId=$user->userType->UserTypeId;
        $dateMovemented = now();
        $dateMovemented = $dateMovemented->format('Y-m-d');
       
        
        Transaction::create([
            'UserFId' => $userConnected,
            'BrancheFId' => $request->BrancheFId,
            'UserTypeFId'=>$request->UserTypeFId,
            'CurrencyFId'=>$request->CurrencyFId,
            'FromBranchId'=>$request->BrancheFId,
            'Number'=>$request->Number,
            'Amount'=>$request->Amount,
            'Note'=>$request->Note,
            'DateMovemented'=>$dateMovemented ,
        ]);
         
        return response()->json([
            'status'=>200,
            'message'=>"Demande envoyée",
        ],200);
        
    }
    public function show(strinG $id){
        $transaction=Transaction::find($id);
        return response()->json($transaction);
        // $user=$transaction->user;
        
    }
    public function update(Request $request, string $id){

        function validatedNumberFormat($number) {
            $validateNumber = substr($number, 0, 3);
            return in_array($validateNumber, ['099', '097']);
        }
        $transaction=Transaction::find($id);

        if(!validatedNumberFormat($request->Number)) {
            return response()->json(['error' => 'Ceci n\'est pas un numero airtel']);
        }
        if(strlen($request->Number) !== 10){
             return response()->json(['error' => 'Entrer un numero valide']);
        }

        // Récupérer la nouvelle valeur de FromBranchId
        $newFromBranchId = $request->input('FromBranchId');

        // Mettre à jour BrancheFId dans users
        // User::where('UserId', $transaction->UserFId)
        //     ->update(['BrancheFId' => $newFromBranchId]);

        $transaction->update($request->all());
        return response()->json([
            'status'=>201,
            'message'=>'Modification reussie'
        ]);

    }
    public function destroy(string $id){
        $transaction=Transaction::find($id);
        $transaction->delete();
        return response()->json(['message'=>"Suppression reussie"],200);
    }

}
