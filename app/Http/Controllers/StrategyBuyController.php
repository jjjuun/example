<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategyBuyController extends Controller
{
    //
    function strategyBuyShow(Request $request){

        // 入力フォームの内容をセッションに格納する
        $input = $request->session()->get("form_input_buy");


        return view("estate.strategy_buy",[
            "myEstates" => $myEstates ?? [],
            "input" => $input
        ]);

    }

    public static function strategyBuyPost(Request $request){

        $input_buy = $request->all();

        // dd("input_strategyBuyPost",$input);

        //セッションに書き込む（key名：form_input）
        $request->session()->put("form_input_buy", $input_buy);

        // dd("request_strategyBuyPost",$request);

        // 入力後のリダイレクト先を設定
        // フォーム内容の引継ぎ：https://cly7796.net/blog/php/take-over-the-value-when-you-redirect-in-laravel-of-form/
        return redirect("/strategy_possess");


    }
}