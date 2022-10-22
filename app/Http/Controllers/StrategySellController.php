<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategySellController extends Controller
{
    //
    function strategySellShow(Request $request){

        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();

        return view("estate.strategy_sell",[
            "myEstates" => $myEstates ?? [],
        ]);
    }

    function strategySellPost(Request $request){

        $input_sell = $request->all();

        // 入力フォームの内容をセッションに格納する
        $request->session()->put("form_input_sell", $input_sell);
        // dd("session",$request->session());//attributeの中にinput_buyとinput_possessとinput_sellが格納されている。

        // 入力後のリダイレクト先を設定
        return redirect("/strategy_sell_confirm");
    }

    function strategySellConfirm(Request $request){

        $input_sell = $request->session()->get("form_input_sell");

        return view("estate.strategy_sell_confirm",[
            "input_sell" => $input_sell,
        ]);
    }

    function strategySellSend(Request $request){

        $input_sell = $request->session()->get("form_input_sell");

        // 「戻る」をクリックした時の処理
        if($request->has("back")){
            //戻るボタンが押された時の処理
            return redirect("/strategy_sell")->withInput($input_sell);
        }

        return redirect("/strategy_output");
    }

}