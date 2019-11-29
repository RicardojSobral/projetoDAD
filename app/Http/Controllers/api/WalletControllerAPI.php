<?php

namespace App\Http\Controllers;

use App\Http\Resources\Wallet as WalletResource;

use Illuminate\Http\Request;
use App\Wallet;
use Illuminate\Support\Facades\DB;

class WalletControllerAPI extends Controller
{
    public function index()
    {
        return WalletResource::collection(Wallet::all());
    }

    public function countWallets()
    {
        $count = DB::table('wallets')->count();
        return $count;
    }
}
