<?php

namespace App\Http\Controllers;

use Faker\Provider\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use function GuzzleHttp\Promise\all;
use App\Http\Controllers\Controller;

use App\Http\Resources\User as UserResource;

use App\User;
use Hash;


class UserControllerAPI extends Controller
{

    public function index(Request $request) {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function show($id) {
        return new UserResource(User::find($id));
    }

    public function store(Request $request) {
        $request->validate([
            'name'      => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'min:3',
            'nif'       => 'integer|min:9|max:9',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1080',
        ]);

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->save();

        return response()->json(new UserResource($user), 201);
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
        //dd($request->old_password);
        return response('Old password incorrect');    
    }

}