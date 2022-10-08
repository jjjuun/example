<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategySellController extends Controller
{
    //
    function strategySell(Request $request){

        // 論理削除されていないマイ物件をDBから取得する
        $myEstates = Estate::where("user_id","=", Auth::id())
            ->where("DB_status", "=", 1)
            ->get();

        // フォームの内容を取得する
        $strategy_values = $request->all();

        if(!$strategy_values) {
            return view("estate.strategy_sell",[
                "myEstates" => $myEstates ?? [],
            ]);

        } else {
            /*
            出口戦略計算の前提
            ・価格を計算する時は、すべて万円単位ではなく円単位とする。
            ・最終CFの値で出口戦略（物件をいつ売るか？）を判断できるようにする
            ・最終CF＝購入CF＋保有CF＋売却CFで算出し、コーディングも購入CF、保有CF、売却CFのブロックで実施する。
            ・計算結果は表とグラフで出力する。（出力する変数は未確定）
            */

            /*
            【購入CF】
            ・不動産ローン＋自己資金ー（物件費用＋仲介手数料＋収入印紙代＋登記費用＋その他）
            ・本CFの変数はすべて時系列変化はないものとする。
            */

            // ★収入
                //不動産ローン（円）
                $loan = $strategy_values["loan"] * 10000;

                //自己資金（円）
                $own_resource = $strategy_values["loan_own_resource"] * 10000;
            // ★収入

            // ★支出
                //物件費用（円）
                // フォームから取得した内容を計算しやすいように変数にする
                $selected_my_estate = $strategy_values["my_estate"];//フォームで選択したマイ物件の配列番号を取得する★配列の番号とデータベースのid番号がひとつずつずれるのを何とかしたい
                $my_estate_price = $myEstates[$selected_my_estate]["BuyPrice"] * 10000;//フォームで選択された物件の購入価格

                // 仲介手数料（円）
                $buy_brokerage_fee = $my_estate_price * ( $strategy_values["buy_brokerage_rate"] / 100 );

                // 収入印紙代（円）
                $buy_stamp_fee = $strategy_values["buy_stamp_fee"];

                // 登記費用（円）
                $buy_registration_fee = $strategy_values["buy_registration_fee"];
                
                // その他（円）
                $buy_other_fee = $strategy_values["buy_other_fee"];
            // ★支出

            // 購入キャッシュフロー（円）
            $buy_CF = ($loan + $own_resource) - ($my_estate_price + $buy_brokerage_fee + $buy_stamp_fee + $buy_registration_fee + $buy_other_fee);
        }
        

        return view("strategy_sell",[
            "myEstates" => $myEstates ?? [],
            "buy_CF" => $buy_CF ?? null,
        ]);
    }
}