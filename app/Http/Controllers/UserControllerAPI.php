<?php

namespace App\Http\Controllers;

use Faker\Provider\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use function GuzzleHttp\Promise\all;
use App\Http\Controllers\Controller;

use App\Http\Resources\User as UserResource;

use App\User;
use App\Wallet;
use App\Movement;
use Hash;
use \DateTime;

class UserControllerAPI extends Controller
{

    public function index(Request $request) {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function getFilteredUsers(Request $request){

        if(!is_null($request->name) || !is_null($request->type) || !is_null($request->email) || !is_null($request->active)){

            $users = User::with('wallet')->select('*');

            if(!is_null($request->name)){
                $users = $users->where('name', 'like', $request->name . '%');
            }
            if(!is_null($request->type)){
                $users = $users->where('type', $request->type);
            }
            if(!is_null($request->email)){
                $users = $users->where('email', 'like', $request->email . '%');
            }
            if(!is_null($request->active)){
                if($request->type == null || $request->type == 'u'){
                    $users = $users->where('active', $request->active);
                }else{
                    return "Can't search by status and type if type is different form 'Platform User'!";
                }
            }

            $users = $users->paginate(10);

        }else{
            $users = User::with('wallet')->select('*')->paginate(10);
        }
        return $users;
    }

    public function show($id) {
        return new UserResource(User::find($id));
    }


    public function store(Request $request) {
        $request->validate([
            'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'min:3',
            'nif'       => 'integer|digits:9',
            //'photo'     => 'image|max:1080',
        ]);

        if($request->photo['base64']) {
            $photo = $request->photo;
            $base64_string = explode(',', $photo['base64']);
            $imageBin = base64_decode($base64_string[1]);

            if (!Storage::disk('public')->exists('fotos/' . $photo['name'])) {
                Storage::disk('public')->put('fotos/' . $photo['name'], $imageBin);
            }
        }

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->photo = $request->photo['base64'] ? $request->photo['name'] : null;
        $user->save();

        $wallet = new Wallet();
        $wallet->fill(['id' => $user->id, 'email' => $user->email, 'balance' => 0]);
        $wallet->save();

        return response()->json(new UserResource($user), 201);
    }

    public function storeOpAdmin(Request $request) {
        $request->validate([
            'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'min:3',
            'type' => 'required|in:a,o,u',
            'photo' => 'required',//|image|mimes:jpeg,png,jpg,gif|max:1080
        ]);

        $base64_string = explode(',', $request->photoBase64);
        $imageBin = base64_decode($base64_string[1]);    
        if (!Storage::disk('public')->exists('fotos/' . $request->photo)) {
            Storage::disk('public')->put('fotos/' . $request->photo, $imageBin);
        }     

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->save();

        return new UserResource($user);
    }


    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        if($user->type == "u"){
            if($request->photoBase64){                            //Foto nova
                $request->validate([
                    'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                    'nif'       => 'integer|digits:9',
                    //'photo' => 'image|mimes:jpeg,png,jpg,gif|max:1080',
                ]);

                $base64_string = explode(',', $request->photoBase64);
                $imageBin = base64_decode($base64_string[1]);
                if (!Storage::disk('public')->exists('fotos/' . $request->photo)) {
                    Storage::disk('public')->delete('fotos/' . $user->photo);
                    Storage::disk('public')->put('fotos/' . $request->photo, $imageBin);
                }

            }else{
                $request->validate([
                    'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                    'nif'       => 'integer|digits:9',

                ]);
            }
        }else{
            if($request->photoBase64){                            //Foto nova
                $request->validate([
                    'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                    //'photo' => 'image|mimes:jpeg,png,jpg,gif|max:1080',
                ]);

                $base64_string = explode(',', $request->photoBase64);
                $imageBin = base64_decode($base64_string[1]);
                if (!Storage::disk('public')->exists('fotos/' . $request->photo)) {
                    Storage::disk('public')->delete('fotos/' . $user->photo);
                    Storage::disk('public')->put('fotos/' . $request->photo, $imageBin);
                }

            }else{
                $request->validate([
                    'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                ]);
            }
        }

        $user->update($request->except('photoBase64'));
        return new UserResource($user);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

    public function emailAvailable(Request $request) {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } elseif ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }

        return response()->json($totalEmail = 0);
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function alterarPassword(Request $request){
        $request->validate([
            'old_password' => 'required|min:3',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ],
        [
            'old_password.required' => 'É necessario inserir a password antiga',
            'password.required' => 'É necessario inserir a nova password',
            'password_confirmation.required' => 'É necessario confirmar a password',
            'old_password.min' => 'A password antiga precisa no minimo de 8 caracteres',
            'password.min' => 'A nova password precisa no minimo de 8 caracteres',
            'password_confirmation.min' => 'A confirmacao da password precisa no minimo de 8 caracteres',
            'password_confirmation.same' => 'A password da confirmacao tem de ser igual a password'
        ]);

        $id = $request->userId;
        $user = User::findOrFail($id);
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return new UserResource($user);
        }
        return response('Old password incorrect');
    }

    public function deactivateUser($id){
        $user = User::findOrFail($id);
        $balance = DB::table('wallets')->select('balance')->where('id', $id)->get();
        if($balance[0]->balance == 0){
            $user->active = 0;
            $user->save();
        }else{
            return "Wallet Balance must be 0.00 to deactivate a user!";
        }
        return new UserResource($user);
    }

    public function activateUser($id){
        $user = User::findOrFail($id);
        $user->active = 1;
        $user->save();
        return new UserResource($user);
    }


    ///////////////////////////// USER STATISTICS //////////////////////////////


    public function getTotals($id){
        $incomesCount = DB::table('movements')->select('value')->where('wallet_id', $id)->where('type', 'i')->get();
        $expensesCount = DB::table('movements')->select('value')->where('wallet_id', $id)->where('type', 'e')->get();

        $income = 0;
        for($i=0; $i < count($incomesCount); $i++){
            $income = $income + $incomesCount[$i]->value;
        }

        $expenses = 0;
        for($i=0; $i < count($expensesCount); $i++){
            $expenses = $expenses + $expensesCount[$i]->value;
        }

        $totals[0] = number_format((float)$income, 2, '.', '');
        $totals[1] = number_format((float)$expenses, 2, '.', '');
        return $totals;
    }

    public function expensesByCategory($id){
        $categories = DB::table('categories')->select('id', 'name')->where('type', 'e')->get();

        for ($i=0; $i < count($categories); $i++){
            $expensesCount[$i] = DB::table('movements')->select('value')->where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'e')->get();
            $countMoneyExpense = 0;
            for($j=1; $j < count($expensesCount[$i]); $j++){
                $countMoneyExpense = $countMoneyExpense + $expensesCount[$i][0]->value;
            }
            $totalByCategorie[$i]['ammount'] = number_format((float)$countMoneyExpense, 2, '.', '');
            $totalByCategorie[$i]['category'] = $categories[$i]->name;
        }
        //formatar dados para dataset
        $total = count($totalByCategorie);
        for($i = 0; $i < $total; $i++){
            $labels[] = $totalByCategorie[$i]['category'];
            $rows[] = $totalByCategorie[$i]['ammount'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return $data;
    }

    public function incomesByCategory($id){
        $categories = DB::table('categories')->select('id', 'name')->where('type', 'i')->get();

        for ($i=0; $i < count($categories); $i++){
            $incomesCount[$i] = DB::table('movements')->select('value')->where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'i')->get();
            $countMoneyIncome = 0;
            for($j=1; $j < count($incomesCount[$i]); $j++){
                $countMoneyIncome = $countMoneyIncome + $incomesCount[$i][0]->value;
            }
            $totalByCategorie[$i]['ammount'] = number_format((float)$countMoneyIncome, 2, '.', '');
            $totalByCategorie[$i]['category'] = $categories[$i]->name; 
        }
        //formatar dados para dataset
        $total = count($totalByCategorie);
        for($i = 0; $i < $total; $i++){
            $labels[] = $totalByCategorie[$i]['category'];
            $rows[] = $totalByCategorie[$i]['ammount'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return $data;
    }

    public function balanceThroughTime($id){
        //todos ordenados por mes
        $dates = DB::table('movements')->select('date')->where('wallet_id', $id)->orderBy('date', 'asc')->get();

        $firstYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("Y");
        $firstMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("m");
        $lastYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("Y");
        $lastMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("m");
        $count = 0;
        
        for ($i=$firstYear; $i <= $lastYear; $i++){ //percorrer anos
            if($count == 0){
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){ //percorrer meses                
                    $datesMonth = DB::table('movements')->select('date', 'start_balance')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $j . '%')->orderBy('date', 'asc')->get();
                    $firtsDates[$count] = $datesMonth[0];
                    $count = $count+1;
                } 
            }else{
                $firstMonth = 1; //passar firstMonth para 1 na primeira mudança de ano
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){ //percorrer meses
                    if($j < 10){
                        $h = "0".$j;
                    }else{
                        $h = $j;
                    }                           
                    $datesMonth = DB::table('movements')->select('date', 'start_balance')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $h . '%')->orderBy('date', 'asc')->get();
                    $firtsDates[$count] = $datesMonth[0];
                    $count = $count+1;
                } 
            }
        }
        //formatar dados para dataset
        $total = count($firtsDates);
        for($i = 0; $i < $total; $i++){
            $labels[] = $firtsDates[$i]->date;
            $rows[] = $firtsDates[$i]->start_balance;
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return $data;
    }

    public function expensesThroughTime($id){

        $dates = DB::table('movements')->select('date')->where('wallet_id', $id)->orderBy('date', 'asc')->get();

        $firstYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("Y");
        $firstMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("m");
        $lastYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("Y");
        $lastMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("m");
        $count = 0;

        for ($i=$firstYear; $i <= $lastYear; $i++){ //percorrer anos
            if($count == 0){
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){                
                    $expensesMonth = DB::table('movements')->select('date', 'value')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $j . '%')->where('type', 'e')->orderBy('date', 'asc')->get();
                    
                    $value = 0;
                    $size = count($expensesMonth);
                    for($h = 0; $h < $size; $h++){
                        $value = $value + $expensesMonth[$h]->value;
                    }
                    $totalExpenseMonth[$count]['value'] = number_format((float)$value, 2, '.', '');
                    $totalExpenseMonth[$count]['date'] = $i.'-'.$j;
                    
                    $count = $count + 1;
                }
            }else{
                $firstMonth = 1;
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){     
                    if($j < 10){
                        $m = "0".$j;
                    }else{
                        $m = $j;
                    }             
                    $expensesMonth = DB::table('movements')->select('date', 'value')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $m . '%')->where('type', 'e')->orderBy('date', 'asc')->get();
                    
                    $value = 0;
                    $size = count($expensesMonth);
                    for($h = 0; $h < $size; $h++){
                        $value = $value + $expensesMonth[$h]->value;
                    }
                    $totalExpenseMonth[$count]['value'] = number_format((float)$value, 2, '.', '');
                    $totalExpenseMonth[$count]['date'] = $i.'-'.$m;
                    
                    $count = $count + 1;
                }
            }
        }
        //formatar dados para dataset
        $total = count($totalExpenseMonth);
        for($i = 0; $i < $total; $i++){
            $labels[] = $totalExpenseMonth[$i]['date'];
            $rows[] = $totalExpenseMonth[$i]['value'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return $data;
    }

    public function incomesThroughTime($id){

        $dates = DB::table('movements')->select('date')->where('wallet_id', $id)->orderBy('date', 'asc')->get();
          
        $firstYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("Y");
        $firstMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[0]->date)->format("m");
        $lastYear = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("Y");
        $lastMonth = DateTime::createFromFormat('Y-m-d H:i:s' ,$dates[count($dates)-1]->date)->format("m");
        $count = 0;

        for ($i=$firstYear; $i <= $lastYear; $i++){ //percorrer anos
            if($count == 0){
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){                
                    $incomesMonth = DB::table('movements')->select('date', 'value')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $j . '%')->where('type', 'i')->orderBy('date', 'asc')->get();
                    
                    $value = 0;
                    $size = count($incomesMonth);
                    for($h = 0; $h < $size; $h++){
                        $value = $value + $incomesMonth[$h]->value;
                    }
                    $totalIncomeMonth[$count]['value'] = number_format((float)$value, 2, '.', '');
                    $totalIncomeMonth[$count]['date'] = $i.'-'.$j;
                    
                    $count = $count + 1;
                }
            }else{
                $firstMonth = 1;
                for ($j=$firstMonth; $j < $lastMonth+1; $j++){     
                    if($j < 10){
                        $m = "0".$j;
                    }else{
                        $m = $j;
                    }             
                    $incomesMonth = DB::table('movements')->select('date', 'value')->where('wallet_id', $id)->where('date', 'like', $i . '-' . $m . '%')->where('type', 'i')->orderBy('date', 'asc')->get();
                    
                    $value = 0;
                    $size = count($incomesMonth);
                    for($h = 0; $h < $size; $h++){
                        $value = $value + $incomesMonth[$h]->value;
                    }
                    $totalIncomeMonth[$count]['value'] = number_format((float)$value, 2, '.', '');
                    $totalIncomeMonth[$count]['date'] = $i.'-'.$m;
                    
                    $count = $count + 1;
                }
            }
        }
        //formatar dados para dataset
        $total = count($totalIncomeMonth);
        for($i = 0; $i < $total; $i++){
            $labels[] = $totalIncomeMonth[$i]['date'];
            $rows[] = $totalIncomeMonth[$i]['value'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return $data;
    }

}
