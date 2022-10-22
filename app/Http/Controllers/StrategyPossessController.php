<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\StrategyBuyController;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategyPossessController extends Controller
{

    function strategyPossessShow(Request $request){

        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();

        // dd("session",$request->session());//attributeの中にkey名がinput_buyとして、フォームで入力した内容が格納されている。

        return view("estate.strategy_possess",[
            "myEstates" => $myEstates ?? [],

        ]);
    }

    function strategyPossessPost(Request $request){

        $input_possess = $request->all();

        // 入力フォームの内容をセッションに格納する
        $request->session()->put("form_input_possess", $input_possess);
        // dd("session",$request->session());//attributeの中にinput_buyとinput_possessが格納されている。

        // 入力後のリダイレクト先を設定
        return redirect("/strategy_possess_confirm");
    }

    function strategyPossessConfirm(Request $request){

        $input_possess = $request->session()->get("form_input_possess");

        return view("estate.strategy_possess_confirm",[
            "input_possess" => $input_possess,
        ]);
    }

    function strategyPossessSend(Request $request){

        $input_possess = $request->session()->get("form_input_possess");

        // 「戻る」をクリックした時の処理
        if($request->has("back")){
            //戻るボタンが押された時の処理
            return redirect("/strategy_possess")->withInput($input_possess);
        }

        return redirect("/strategy_sell");
    }
}