<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ↓↓↓【20221003】パスワードリセット機能作成のため追加↓↓↓
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
// ↑↑↑【20221003】パスワードリセット機能作成のため追加↑↑↑

class UserController extends Controller
{
    function index(){

        $user_list = User::where("id","=", Auth::id())
            ->get();

        // var_dump(Auth::id(),$user_list);

        return view("user.index",[
            "user_list" => $user_list
        ]);
    }
    
    function edit(Request $request,$id){

        // 該当するidのユーザー情報をusersテーブルから取得する
        $edit_user = User::where("id", $id)
            ->where("id", Auth::id())
            ->first();

        // dd($edit_user);


        return view('user.edit',[
            "edit_user" => $edit_user
        ]);
    }

    function update(Request $request, $id){

        // フォームに入力した更新内容を取得する
        $update_input = $request->except("image");
        // dd($update_input);//->結果は以下
        /**
         * ^ array:5 [▼
         * "_token" => "udBWW5q5Ozc25mnyOQNefz36kUPNp8U85F21JuhP"
         * "name" => "kato"
         * "email" => "katojun.nit@gmail.com"
         * "self_introduct" => "aaaaaaaaaaaaaaaa"
         * "user_style" => "投資家"
         * ]
         */
        // 

        // フォームから入力された画像用の変数（$image）を指定する
        $image = $request->file("image");
        // dd($image);//->クラスが表示。うまく画像をとれているっぽい。
        
        if($image){
            // アップロードされた画像を保存する
            $path = $image->store("uploads","public");

            // 画像の保存に成功したらDBに記録する
            if($path){
                $request->session()->put("form_input_image",[ 
                    "file_name" => $image->getClientOriginalName(),
                    "file_path" => $path
                ]);
            }
        }

        $image = $request->session()->get("form_input_image");

        // dd($image);//->結果は以下。
        /** 
         * ^ array:2 [▼
         * "file_name" => "13832596202494.jpg"
         * "file_path" => "uploads/rt35eCLdg4GZFJ98EgbGsziVDPCRlfk2Ghw8c54G.jpg"
         * ]
        */
        // 

        $form = new User();
        if($image){
            $form->file_name = $image["file_name"];
            $form->file_path = $image["file_path"];
        }

        // dd($form);//->結果はクラスが表示された。

        // それぞれの更新内容を$update連想配列に格納し、User::updateを使えるようにする
        $update = [
            "name" => $update_input["name"],
            "email" => $update_input["email"],
            "self_introduct" => $update_input["self_introduct"],
            "user_style" => $update_input["user_style"],
            "file_name" => $image["file_name"],
            "file_path" => $image["file_path"],
        ];

        // dd($update);

        // usersテーブルのidが一致した物に対して、updateをする。
        User::where("id","=",Auth::id())->update($update);

        // "/user/index"にリダイレクト
        return redirect("user/index");
    }

    function editPassword($id){

        // 該当するidのユーザー情報をusersテーブルから取得する
        $edit_password = User::where("id", $id)
            ->where("id", Auth::id())
            ->first();
        
        // dd($edit_password,$id);

        return view('user.password_edit',[
            "edit_password" => $edit_password
        ]);
    }


    function updatePassword(Request $request,$id){

        $user = Auth::user();

        
        // 確認用パスワードと登録しているパスワードが一致していない場合
        if(!password_verify($request->current_password,$user->password)){

            return redirect()
                ->route("password.edit",["id" => $id])
                ->with('warning','入力した旧パスワードと登録しているパスワードが一致しません');

        // 確認用パスワードと登録しているパスワードが一致している場合
        } elseif(password_verify($request->current_password,$user->password)) {

            
            // 新しいパスワードと新しいパスワードの確認が一致している場合
            if($request->new_password === $request->new_password_confirm){

                // new_passwordをハッシュ化する
                $hashed_new_password = password_hash($request->new_password,PASSWORD_DEFAULT);

                // それぞれの更新内容を$update連想配列に格納し、User::updateを使えるようにする
                $update = [
                    "password" => $hashed_new_password
                ];
                
                // usersテーブルのidが一致した物に対して、updateをする。
                User::where("id","=",Auth::id())->update($update);

                return redirect()
                ->route("password.edit",["id" => $id])
                ->with('success_password','パスワードの変更が終了しました');

            
            // 新しいパスワードと新しいパスワードの確認が一致していない場合
            } elseif($request->new_password !== $request->new_password_confirm){


                return redirect()
                ->route("password.edit",["id" => $id])
                ->with('warning','新しいパスワードと確認用の新しいパスワードが一致していません');
            }
        }
    }

    // ↓↓↓【20221003】パスワードリセット機能作成のため追加↓↓↓

    // 「パスワードを忘れた人はこちら」をクリックした後にアクセスするページを返す
    function requestResetPassword() {
        
        return view('user.password_reset');
    }


    // 上記で入力したメールアドレスに、パスワードリセットページのリンクを送信する
    function sendResetNotification(Request $request){

        // フォーム入力内容のバリエーション
        $request->validate([
            "email" => "required|email"
        ]);

        // パスワードリセットリンクを送信する
        $status = Password::sendResetLink(
            $request->only("email")
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(["status" => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }


    // パスワードリセットページで新しいパスワードを入力するフォームを作成する
    function sendResetPasswordForm($token){
        return view('user.password_reset_edit',[
            "token" => $token
        ]);
    }


    // パスワードをリセットするためのフォームに入力する
    function resetPassword(Request $request){

        // フォーム入力内容のバリエーション
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // パスワードリセットリクエストの資格情報を検証する？？？
        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
        
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route("login")->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // ↑↑↑【20221003】パスワードリセット機能作成のため追加↑↑↑


}
