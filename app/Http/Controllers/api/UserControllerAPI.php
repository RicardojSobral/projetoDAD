<?php

namespace App\Http\Controllers\api;

use Intervention\Image\Image;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use function GuzzleHttp\Promise\all;
use App\Http\Controllers\Controller;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Wallet as WalletResource;

use App\User;
use App\Wallet;
use Hash;
use File;


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
        /*$request->validate([
            'name'      => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'min:3',
            'nif'       => 'integer|min:9|max:9',
            'photo'     => 'image|max:1080',
        ]);*/

        dd($request->get('photo')->name);

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->photo = $request->get('photo') ? $request->get('photo')->name : null;
        $user->save();

        $wallet = new Wallet();
        $wallet->fill(['id' => $user->id, 'email' => $user->email, 'balance' => 0]);
        $wallet->save();

        if($request->get('photoReader')) {
            $photo = $request->get('photoReader');
            $name = $user->id . '_' . $photo->name;
            Image::make($request->get('photoReader'))->save(public_path('fotos/').$name);
        }

        /*$image= new FileUpload();
        $image->image_name = $name;
        $image->save();*/



        /*$fileName = $user->id . '_' . $user->photo.name;
        $request->file->move(public_path('fotos'), $fileName);*/

        return response()->json(new UserResource($user), 201);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'      => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email'     => 'required|email|unique:users,email',
            'nif'       => 'integer|min:9|max:9',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1080',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());
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
}
