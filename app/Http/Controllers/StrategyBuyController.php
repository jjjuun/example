<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategyBuyController extends Controller
{
    //
    function strategyBuyShow(Request $request){

        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();

        // dd("myEstates",$myEstates[0]["EstateName"],request()->get("myEstates"));
        return view("estate.strategy_buy",[
            "myEstates" => $myEstates ?? [],
        ]);

    }


    function strategyBuyPost(Request $request){

        $input_buy = $request->all();

        // dd("input_strategyBuyPost",$input);

        //セッションに書き込む（key名：form_input）
        $request->session()->put("form_input_buy", $input_buy);
        // dd("session",$request->session());//attributeの中にkey名がinput_buyとして、フォームで入力した内容が格納されている。

        // 入力後のリダイレクト先を設定
        return redirect("/strategy_buy_confirm");
    }

    function strategyBuyConfirm(Request $request){

        $input_buy = $request->session()->get("form_input_buy");

        return view("estate.strategy_buy_confirm",[
            "input_buy" => $input_buy,
        ]);
    }

    function strategyBuySend(Request $request){

        $input_buy = $request->session()->get("form_input_buy");

        // 「戻る」をクリックした時の処理
        if($request->has("back")){
            //戻るボタンが押された時の処理
            return redirect("/strategy_buy")->withInput($input_buy);
        }

        return redirect("/strategy_possess");
    }

    function strategyBuyCalcLoan(Request $request){

        $input_buy = $request->session()->get("form_input_buy");

        /**
         * 共通で使う変数を指定する
         */
        // ローン金額
        $loan = ($input_buy["buy_price"] - $input_buy["loan_own_resource"]) * 10000;
        // ローン返済期間
        $loan_repayment_duration = $input_buy["loan_repayment_duration"];
        // 月利
        $loan_interest_rate_month = ($input_buy["loan_interest_rate"] / 100) / 12;
        // 返済回数
        $loan_repayment = $input_buy["loan_repayment_duration"] * 12;

        // ローン返済額の繰り返し計算
        $calc_arrays_loan = array();



        // 元利均等返済の場合
        if($input_buy["loan_repayment_method"] === "元利均等返済"){


            for ($i = 0; $i < $loan_repayment_duration; $i++){
                for ($j = 0; $j < 12; $j++){

                    // ローン毎月返済額の中間変数（分子）
                    $calc_arrays_loan[$i][$j]["loan_repayment_m_mid_child"] = $loan * $loan_interest_rate_month * pow((1 + $loan_interest_rate_month),$loan_repayment);

                    // ローン毎月返済額の中間変数（分母）
                    $calc_arrays_loan[$i][$j]["loan_repayment_m_mid_mother"] = pow((1 + $loan_interest_rate_month),$loan_repayment) - 1;

                    // ローン毎月返済額
                    $calc_arrays_loan[$i][$j]["loan_repayment_month"] = $calc_arrays_loan[$i][$j]["loan_repayment_m_mid_child"] / $calc_arrays_loan[$i][$j]["loan_repayment_m_mid_mother"];

                    // ローン返済初回
                    if($i === 0 && $j === 0){

                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $loan * $loan_interest_rate_month;

                        // 元金返済額
                        $calc_arrays_loan[$i][$j]["principal_repayment"] = $calc_arrays_loan[$i][$j]["loan_repayment_month"] -  $calc_arrays_loan[$i][$j]["interest_payment"];
                    
                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $loan - $calc_arrays_loan[$i][$j]["principal_repayment"];

                    // ローン返済2回目以降
                    } elseif($i > 0 && $j === 0) {

                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $calc_arrays_loan[$i-1][$j+11]["loan_remain"] * $loan_interest_rate_month;

                        // 元金返済額
                        $calc_arrays_loan[$i][$j]["principal_repayment"] = $calc_arrays_loan[$i][$j]["loan_repayment_month"] -  $calc_arrays_loan[$i][$j]["interest_payment"];

                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $calc_arrays_loan[$i-1][$j+11]["loan_remain"] - $calc_arrays_loan[$i][$j]["principal_repayment"];

                    } else {

                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $calc_arrays_loan[$i][$j-1]["loan_remain"] * $loan_interest_rate_month;

                        // 元金返済額
                        $calc_arrays_loan[$i][$j]["principal_repayment"] = $calc_arrays_loan[$i][$j]["loan_repayment_month"] -  $calc_arrays_loan[$i][$j]["interest_payment"];

                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $calc_arrays_loan[$i][$j-1]["loan_remain"] - $calc_arrays_loan[$i][$j]["principal_repayment"];

                    }
                }
            }
        }

        // 元金均等返済の場合
        if($input_buy["loan_repayment_method"] === "元金均等返済"){

            for ($i = 0; $i < $loan_repayment_duration; $i++){
                for ($j = 0; $j < 12; $j++){

                    // 元金返済額（固定）
                    $calc_arrays_loan[$i][$j]["principal_repayment"] = $loan / ($loan_repayment_duration * 12);

                    // ローン返済初回
                    if($i === 0 && $j === 0){

                        
                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $loan * $loan_interest_rate_month;

                        // ローン毎月返済額
                        $calc_arrays_loan[$i][$j]["loan_repayment_month"] = $calc_arrays_loan[$i][$j]["principal_repayment"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $loan - $calc_arrays_loan[$i][$j]["loan_repayment_month"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                    // ローン返済2回目以降
                    } elseif($i > 0 && $j === 0) {
                        
                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $calc_arrays_loan[$i-1][$j+11]["loan_remain"] * $loan_interest_rate_month;

                        // ローン毎月返済額
                        $calc_arrays_loan[$i][$j]["loan_repayment_month"] = $calc_arrays_loan[$i][$j]["principal_repayment"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $calc_arrays_loan[$i-1][$j+11]["loan_remain"] - $calc_arrays_loan[$i][$j]["loan_repayment_month"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                    } else {

                        
                        // 利息返済額
                        $calc_arrays_loan[$i][$j]["interest_payment"] = $calc_arrays_loan[$i][$j-1]["loan_remain"] * $loan_interest_rate_month;

                        // ローン毎月返済額
                        $calc_arrays_loan[$i][$j]["loan_repayment_month"] = $calc_arrays_loan[$i][$j]["principal_repayment"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                        // ローン残債
                        $calc_arrays_loan[$i][$j]["loan_remain"] = $calc_arrays_loan[$i][$j-1]["loan_remain"] - $calc_arrays_loan[$i][$j]["loan_repayment_month"] + $calc_arrays_loan[$i][$j]["interest_payment"];

                    }
                }
            }
            // dd("元金均等返済",$calc_arrays_loan);
        }

        // 返済金利、返済総額を算出する（返済方式関係なく共通）
        for ($i = 0; $i < $loan_repayment_duration; $i++){
            for ($j = 0; $j < 12; $j++){

                // ローン返済初回
                if($i === 0 && $j === 0){

                    // 返済金利
                    $calc_arrays_loan[$i][$j]["total_interest_payment"] =  $calc_arrays_loan[$i][$j]["interest_payment"];
                    // 返済総額
                    $calc_arrays_loan[$i][$j]["total_repayment"] =  $calc_arrays_loan[$i][$j]["loan_repayment_month"];

                } elseif($i > 0 && $j === 0) {

                    // 返済金利
                    $calc_arrays_loan[$i][$j]["total_interest_payment"] =  $calc_arrays_loan[$i][$j]["interest_payment"] + $calc_arrays_loan[$i-1][$j+11]["total_interest_payment"];
                    // 返済総額
                    $calc_arrays_loan[$i][$j]["total_repayment"] =  $calc_arrays_loan[$i][$j]["loan_repayment_month"] + $calc_arrays_loan[$i-1][$j+11]["total_repayment"];

                } else {

                    // 返済金利
                    $calc_arrays_loan[$i][$j]["total_interest_payment"] =  $calc_arrays_loan[$i][$j]["interest_payment"] + $calc_arrays_loan[$i][$j-1]["total_interest_payment"];
                    // 返済総額
                    $calc_arrays_loan[$i][$j]["total_repayment"] =  $calc_arrays_loan[$i][$j]["loan_repayment_month"] + $calc_arrays_loan[$i][$j-1]["total_repayment"];

                }
            }
        }

        // ローン返済シミュレーション結果をセッションに格納する
        $request->session()->put($calc_arrays_loan,"calc_arrays_loan");
        
        return view("estate.strategy_buy_confirm",[
            "input_buy" => $input_buy,
            "calc_arrays_loan" => $calc_arrays_loan,
            "loan_repayment" => $loan_repayment,
        ]);
    }

}