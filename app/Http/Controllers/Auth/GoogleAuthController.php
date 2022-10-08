<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
// use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleAuthController extends Controller
{

    public function redirectToGoogle(){


        $url = Socialite::driver('google')->redirect();

        // dd("dd(url)",$url);

        // Google へのリダイレクト
        return $url;//->ここで404 NOT FOUNDになる。
    }

    public function callback(){

        $googleUser = Socialite::driver('google')->stateless()->user();

        // dd("googleUser",$googleUser);//->ここから。
        
        $user = User::where("email", "=", $googleUser->email)->first();

        $password = "dummy";

        if(!$user) {
            $user = User::updateOrCreate([
                "id" => $googleUser->id,
            ], [
                "name" => $googleUser->name,
                "email" => $googleUser->email,
                "token" => $googleUser->token,
                "refreshToken" => $googleUser->refreshToken,
                "password" => $password
            ]);
            // dd("user",$user);
        }

        Auth::login($user);

        return redirect('/home');

        // // Google 認証後の処理
        // // あとで処理を追加しますが、とりあえず dd() で取得するユーザー情報を確認
        // $gUser = Socialite::driver('google')->stateless()->user();
        // // dd($gUser);

        // // email が合致するユーザーを取得
        // $user = User::where('email', $gUser->email)->first();
        // // 見つからなければ新しくユーザーを作成
        // if ($user == null) {
        //     $user = $this->createUserByGoogle($gUser);
        // }
        // // ログイン処理
        // Auth::login($user, true);
        // return redirect('/home');
    }

    public function createUserByGoogle($gUser){
        $user = User::create([
            'name'     => $gUser->name,
            'email'    => $gUser->email,
            'password' => Hash::make(uniqid()),
        ]);
        return $user;
    }
}
