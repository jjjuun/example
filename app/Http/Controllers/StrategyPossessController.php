<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\StrategyBuyController;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategyPossessController extends Controller
{


    function strategyPossessShow(Request $request){

        $test = StrategyBuyController::strategyBuyPost($request);
        dd($test);

        return view("estate.strategy_possess",[
            "myEstates" => $myEstates ?? [],

        ]);
    }

    function strategyPossessPost(Request $request, StrategyBuyController $strategy_buy_controller){

        $input_possess = $request->all();

        // 入力フォームの内容をセッションに格納する
        $input_buy = $request->session()->get("form_input_buy");


        // dd("input_buy_strategyPossessPost",$input_buy);//->strategy_buyのフォーム内容が引き継がれているのを確認できた

        // dd("input_possess_strategyPossessPost",$input);//->strategy_possessでの入力内容のみ


        // strategy_buyとstrategy_possessの入力内容を統合する
        $input_buy_possess = array_merge($input_buy,$input_possess);

        // dd("input_buy_possess_strategyPossessPost",$input_buy_possess);//->strategy_buyとstrategy_possessの入力内容が格納されている

        //セッションに書き込む
        $request->session()->put("form_input_buy_possess", $input_buy_possess);

        dd("request_strategyPossessPost",$request,$input_buy_possess);

        // 入力後のリダイレクト先を設定
        return redirect("/strategy_sell");
    }
}