<?php

namespace App\Http\Controllers;

use App\Http\Resources\Movement as MovementResource;

use Carbon\Carbon;
use App\Wallet;
use App\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementControllerAPI extends Controller
{
    public function createCredit(Request $request) {

        if($request->type_payment == 'bt'){
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0,5000',
                'type_payment' => 'required|in:c,bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                'source_description' => 'required',
            ]);
        }else{
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0,5000',
                'type_payment' => 'required|in:c,bt,mb',
            ]);
        };     

        $walletId = DB::table('wallets')->select('id')->where('email', $request->email)->get(); 
        if($walletId->isEmpty()){
            return response('Email is not valid!');
        }

        $balance = DB::table('wallets')->select('balance')->where('email', $request->email)->get(); 

        //Alterar balance da wallet destino
        $wallet = Wallet::findOrFail($walletId[0]->id);
        $wallet->balance = $balance[0]->balance + $request->value;
        $wallet->save();

        //Date/Time presente
        $date = Carbon::now();
       
        $movement = new Movement();
        $movement->fill($request->all());
        $movement->wallet_id = $walletId[0]->id;
        $movement->type = "i";
        $movement->start_balance = $balance[0]->balance;
        $movement->end_balance = $balance[0]->balance + $request->value;
        $movement->transfer = 0;
        $movement->date = $date->toDateTimeString();
        $movement->save();

        return new MovementResource($movement);
    }
}
