<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GithubAuthController extends Controller
{

    // リダイレクト用URL作成（$providerを複数関数で使うため、別途関数を作成する。）
    function getProvider(){

        // 認証局にリダイレクトするためのクラスを変数$providerに格納する
        $provider = new \League\OAuth2\Client\Provider\Github([
            'clientId'          => '61d2779f4bc432c110be',//Githubから取得
            'clientSecret'      => 'cfb2f6219e99607b49673bed9189419bcbec1855',//Githubから取得
            'redirectUri'       => url("/auth/github/callback"),
        ]);

        // dd($provider);

        // 他の関数等で使えるようにする。
        return $provider;
    }

    // リダイレクトする
    function login(Request $request){

        $provider = $this->getProvider();

        $authUrl = $provider->getAuthorizationUrl();
        
        $request->session()->put("github.auth.state", $provider->getState());
        return redirect($authUrl);
        
    }

    // 上下の関数の間に、Githubの画面（&authUrl）にリダイレクトされるので、認証を許可する。
    // そのあとに、/auth/github/callbackにリダイレクトする


    function callback(Request $request){

        $provider = $this->getProvider();

        // /auth/github/callbackにリダイレクトする際に、"code"と"state"が発行される。※codeだけで問題なし。
        $code = $request->input("code");
        $state = $request->input("state");
        $stored_state = $request->session()->get("github.auth.state");

        //dd($code, $state, $stored_state);

        // "code"をもとに、アクセストークンを発行する
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        // Githubに登録されている情報（ユーザー名など）を変数に格納する
        $github_user = $provider->getResourceOwner($token);
        $nickname = $github_user->getNickname();
        $userid = $github_user->getId();

        $user = User::where("email", "=", $userid . "@github")->first();
        // dd($user);

        // 新規登録の場合（$userがnullの場合）
        // $userにクラスと登録したいGithubのユーザー情報を格納する（＝ユーザー登録完了）
        if(!$user){
            
            $user = new User();
            $user->name = $nickname;
            $user->email = $userid . "@github";
            $user->password = "dummy";
            $user->save();

        }

        // 登録したユーザーで自動でログインする。
        Auth::login($user);

        return redirect("/home");
    }
}
