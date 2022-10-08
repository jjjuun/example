<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ログインしているユーザー情報をViewに渡す
        $user = Auth::user();
        return view('home', compact("user"));
    }

    public function post(Request $request) {
        
        // フォームに入力された値をすべて取得する
        $input = $request->all();

        // バリデーション設定（今のところ特に設定しない）

        //セッションに書き込む（key名：estate_input）
        $request->session()->put("estate_input", $input);
        
        // 入力後のリダイレクト先を設定
        return redirect("/home/confirm");
    }

    function confirm(Request $request){
        // セッションから値を取り出す
        $input = $request->session()->get("estate_input");

        // セッションに値がない時はフォームに戻る
        if(!$input){
            return redirect("/home");
        }

        // 引数inputに変数inputを渡してhome_confirm.blade.phpを返す
        return view("home_confirm",["input" => $input]);
    }

    function send(Request $request){
        // セッションを取り出す
        $input = $request->session()->get("estate_input");

        // セッションに値が無い時はフォームに戻る
        if(!$input){
            return redirect("/home");
        }

        // データベースに格納する変数を指定する
        // key名と同じ名前で変数を指定し、input配列の各keyから値を取り出す。
        $user_id = $input["user_id"];
        $comment = $input["comment"];

        $estate = new Estate();
        $estate->user_id = $user_id;
        $estate->comment = $comment;

        // DBに保存する
        if($request->has("submit")){
            $estate->save();
        }

        // 「戻る」をクリックした時の処理
        if($request->has("back")){
            //戻るボタンが押された時の処理
            return redirect("/home")->withInput($input);
        }

        // セッションを空にする
        $request->session()->forget("estate_input");

        return redirect("/home/complete");
    }

    function complete(){	
		return view("home_complete");
	}
}
