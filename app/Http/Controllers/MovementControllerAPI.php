<?php

namespace App\Http\Controllers;

use App\Http\Resources\Movement as MovementResource;

use Carbon\Carbon;
use App\Category;
use App\Wallet;
use App\User;
use App\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Validator;

class MovementControllerAPI extends Controller
{
    public function createCredit(Request $request) {

        if($request->type_payment == 'bt'){
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|numeric|between:0.01,5000.00',
                'type_payment' => 'required|in:c,bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                'source_description' => 'required',
            ]);
        }else{
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|numeric|between:0.01,5000.00',
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

        //Retornar o user dest para a toast msg
        $user = User::findOrFail($wallet->id);
        return $user;

        //return new MovementResource($movement);
    }

    public function createDebit(Request $request) {

        $destinationWallet = null;
        $wallet_id = Auth::id();


        if ($request->transfer === '0') {
            if($request->type_payment == 'bt') {
                $request->validate([
                    //'type_movement' => 'required',
                    'value'         => 'required|numeric|between:0.01,5000.00',
                    'category_id'   => 'required',
                    'description'   => 'required',
                    'iban'          => 'required|regex:^[A-Z]{2}\d{23}$^'
                ]);
            } elseif ($request->type_payment == 'mb') {
                $request->validate([
                    //'type_movement'         => 'required',
                    'value'                 => 'required|numeric|between:0.01,5000.00',
                    'category_id'           => 'required',
                    'description'           => 'required',
                    'mb_entity_code'        => 'required|digits:5',
                    'mb_payment_reference'  => 'required|digits:9'
                ]);
            } else {
                $request->validate([
                    //'type_movement' => 'required',
                    'value'         => 'required|numeric|between:0.01,5000.00',
                    'category_id'   => 'required',
                    'description'   => 'required',
                    'type_payment'  => 'required|in:bt,mb'
                ]);
            }

        } elseif ($request->transfer === '1') {
            $request->validate([
                //'type_movement'         => 'required',
                'value'                 => 'required|numeric|between:0.01,5000.00',
                'category_id'           => 'required',
                'description'           => 'required',
                'email'                 => 'required|email',
                'source_description'    => 'required'
            ]);

            if (!empty($request->email)) {
                $destinationWallet = DB::table('wallets')->where('email', $request->email)->value('id');
                if(empty($destinationWallet)){
                    return response()->json([msg=>'Email is not valid or user does not have a wallet!'], 422);
                }
            }
        }

        $wallet = new WalletControllerAPI();
        $currentBalance = $wallet->getBalance($wallet_id);

        if ($currentBalance - $request->value < 0) {
            return response()->json(['msg'=>'Your wallet balance must be higher than the value'], 406);
        }

        $endBalance = $currentBalance - $request->value;

        $wallet->updateBalance($wallet_id, $endBalance);
        $wallet->updateBalance($destinationWallet, $currentBalance + $request->value);

        //get date
        $date = Carbon::now();

        $movement = new Movement();
        $movement->fill($request->all());
        $movement->wallet_id = $wallet_id;
        $movement->type = 'e';
        if (!empty($destinationWallet)) {
            $movement->transfer_wallet_id = $destinationWallet;
        }
        $movement->start_balance = $currentBalance;
        $movement->end_balance = $endBalance;
        $movement->date = $date->toDateTimeString();
        $movement->save();

        if ($request->transfer == 1) {
            $data = [
                'wallet_id'             => $destinationWallet,
                'transfer'              => 1,
                'type'                  => 'i',
                'transfer_movement_id'  => $movement->id,
                'transfer_wallet_id'    => $wallet_id,
                'description'           => $request->description,
                'source_description'    => $request->source_description,
                'date'                  => $date->toDateTimeString(),
                'value'                 => $request->value
            ];
            $transfer_movement_id = $this->createTransferIncome($data);
            if (!empty($transfer_movement_id)) {
                $this->updateTransferMovementId($movement->id, $transfer_movement_id);
            }
        }

        //retornar o user dest para a toast do server
        if($destinationWallet != null){
            $user = User::findOrFail($destinationWallet);
            return $user;
        }else{
            return new MovementResource($movement);

        }
    }

    public function createTransferIncome($data) {
        $current_balance = DB::table('wallets')->where('id', $data['wallet_id'])->value('balance');

        $movement = new Movement();
        $movement->fill($data);
        $movement->start_balance = $current_balance;
        $movement->end_balance = $current_balance + $data['value'];
        $movement->save();

        return $movement->id;
    }

    public function updateTransferMovementId($id, $movement_id) {
        DB::table('movements')->where('id', $id)->update(['transfer_movement_id' => $movement_id]);
    }

    public function getFilteredMovements(Request $request){

        if(!is_null($request->id) || !is_null($request->type) || !is_null($request->category) || !is_null($request->type_payment) || !is_null($request->transfer_email) || !is_null($request->data_inf) || !is_null($request->data_sup)){

            $movements = Movement::with('category', 'transfer_wallet', 'transfer_wallet.user')->select('*')->where('wallet_id', $request->user_id);

            if (!is_null($request->id)){
                $movements = $movements->where('id', 'like', $request->id . '%');
            }
            if (!is_null($request->type)){
                $movements = $movements->where('type', $request->type);
            }
            if (!is_null($request->category)){
                $category = DB::table('categories')->select('id')->where('name', $request->category)->get();
                if($category->isEmpty()){
                    return 'Category does not exist!';
                }
                $movements = $movements->where('category_id', $category[0]->id);
            }
            if (!is_null($request->type_payment)){
                $movements = $movements->where('type_payment', $request->type_payment);
            }
            if (!is_null($request->transfer_email)){
                $transfer_email = DB::table('wallets')->select('id')->where('email', $request->transfer_email)->get();
                if($transfer_email->isEmpty()){
                    return 'Transfer e-mail does not exist!';
                }
                $movements = $movements->where('transfer_wallet_id', $transfer_email[0]->id);
            }
            if (!is_null($request->data_sup)){
                $movements = $movements->where('date', '>=', $request->data_sup);
            }
            if (!is_null($request->data_inf)){
                $movements = $movements->where('date', '<=', $request->data_inf);
            }

            $movements = $movements->orderBy('date', 'desc')->paginate(10);

        }else{
            $movements = Movement::with('category', 'transfer_wallet', 'transfer_wallet.user')->select('*')->where('wallet_id', $request->user_id)->orderBy('date', 'desc')->paginate(10);
        }

        return $movements;
    }

    public function update(Request $request, $id){
        $movement = Movement::findOrFail($id);

        $category_name = $request->category['name'];
        $category =  DB::table('categories')->select('id')->where('name', $category_name)->where('type', $movement->type)->get();
        if($category->isEmpty()){
            return 'Category does not exist for this type of movement';
        }

        $movement->category_id = $category[0]->id;
        $movement->description = $request->description;

        $movement->save();
        return new MovementResource($movement);
    }

    public function sendNotificationEmail(Request $request) {
        $to_name = $request->name;
        $to_email = $request->email;

        Mail::send('emails.notification', ['data' => $request->all()], function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name);
            $message->subject('Income added to your wallet');
            $message->from(env('MAIL_USERNAME'),'Wallet Manager');
        });
    }
}
