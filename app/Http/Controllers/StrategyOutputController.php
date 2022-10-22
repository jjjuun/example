<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use Illuminate\Support\Facades\Auth;

class StrategyOutputController extends Controller
{
    //このクラスの最後にセッションの中身を削除する
    function strategyOutputShow(Request $request){
        
        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();


        // セッションから値を取り出し、変数に格納する
        $input_buy = $request->session()->get("form_input_buy");
        $input_possess = $request->session()->get("form_input_possess");
        $input_sell = $request->session()->get("form_input_sell");


        return view("estate.strategy_output",[
            "myEstates" => $myEstates ?? [],
            "input_buy" => $input_buy,
            "input_possess" => $input_possess,
            "input_sell" => $input_sell
        ]);
    }

    function strategyOutputGetMyestate(Request $request){
        // strategyOutputShowから値を取り出す
        $input = $this->strategyOutputShow($request);
        $input_buy = $input["input_buy"];

        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();

        // フォームで選択したマイ物件
        $my_estate = $input_buy["my_estate"];

        // 選択したマイ物件の購入価格を取得する（本来はestatesテーブルのid番号を使いたかったが、マイ物件を１つしか登録していない場合は、meEstates[0]で呼び出せないので以下の方法にした。
        foreach($myEstates as $myEstate){

            if($myEstate["EstateName"] === $my_estate){

                $my_estate_buy_price = $myEstate["BuyPrice"] * 10000;
                $my_estate_property_income = $myEstate["property_income"];
                $my_estate_property_management_cost = $myEstate["property_management_cost"];
                $my_estate_property_maintenance_cost = $myEstate["property_maintenance_cost"];
                $my_estate_property_tax = $myEstate["property_tax"];
                $my_estate_city_plan_tax = $myEstate["city_plan_tax"];
                $my_estate_BuildingYear = $myEstate["BuildingYear"];
                $my_estate_GetYear = $myEstate["GetYear"];
                $my_estate_Structure = $myEstate["Structure"];
                $my_estate_property_std_land_price = $myEstate["property_std_land_price"];
                $my_estate_property_std_house_price = $myEstate["property_std_house_price"];
            }
        }

        // arrayでほかの関数に引き渡す
        return [
            "my_estate" => $my_estate,
            "my_estate_buy_price" => $my_estate_buy_price,
            "my_estate_property_income" => $my_estate_property_income,
            "my_estate_property_management_cost" => $my_estate_property_management_cost,
            "my_estate_property_maintenance_cost" => $my_estate_property_maintenance_cost,
            "my_estate_property_tax" => $my_estate_property_tax,
            "my_estate_city_plan_tax" => $my_estate_city_plan_tax,
            "my_estate_BuildingYear" => $my_estate_BuildingYear,
            "my_estate_GetYear" => $my_estate_GetYear,
            "my_estate_Structure" => $my_estate_Structure,
            "my_estate_property_std_land_price" => $my_estate_property_std_land_price,
            "my_estate_property_std_house_price" => $my_estate_property_std_house_price,
        ];
    }

    function strategyOutputPossessCalcDepreciation(Request $request){

        // 参考文献
        // 償却率表：https://www.nta.go.jp/taxes/shiraberu/taxanswer/shotoku/pdf/2100_02.pdf
        // 減価償却の考え方：https://ieul.jp/column/articles/437/
        // 減価償却の考え方：https://www.rehouse.co.jp/relifemode/column/life-column/mr-7064/#:~:text=%E6%B8%9B%E4%BE%A1%E5%84%9F%E5%8D%B4%E3%81%A8%E3%81%AF%EF%BC%9F,%E3%81%A8%E3%81%97%E3%81%A6%E8%AA%8D%E3%82%81%E3%82%89%E3%82%8C%E3%81%A6%E3%81%84%E3%81%BE%E3%81%99%E3%80%82
        // 構造と耐用年数：https://www.homes.co.jp/cont/money/money_00295/

        // strategyOutputGetMyestateから値を取り出す
        $my_estate = $this->strategyOutputGetMyestate($request);
        $my_estate_BuildingYear = $my_estate["my_estate_BuildingYear"];// 築年数
        $my_estate_GetYear = $my_estate["my_estate_GetYear"];// 物件取得年数
        $my_estate_Structure = $my_estate["my_estate_Structure"];// 建物の構造（SRC：47年、RC：47年、鉄骨（鉄骨の厚み：3～4mm）：27年、鉄骨（鉄骨の厚み：4mm以上）：34年、木造：22年）
        $my_estate_buy_price = $my_estate["my_estate_buy_price"];// 物件購入価格
        $my_estate_property_std_land_price = $my_estate["my_estate_property_std_land_price"];// 固定資産評価額（土地）
        $my_estate_property_std_house_price = $my_estate["my_estate_property_std_house_price"];// 固定資産評価額（家屋）

        // 築年と物件取得年数から経過年を計算する
        $my_estate_elapsed_year = $my_estate_GetYear - $my_estate_BuildingYear;

        // strategyOutputBuyCalcから値を取り出す
        $input_buy = $this->strategyOutputBuyCalc($request);
        $loan_repayment_duration = $input_buy["loan_repayment_duration"];


        // 構造から法定耐用年数を設定する
        if($my_estate_Structure === "木造"){
            $low_service_life = 22;
        }

        if($my_estate_Structure === "鉄骨造_one"){
            $low_service_life = 27;
        }

        if($my_estate_Structure === "鉄骨造_two"){
            $low_service_life = 34;
        }

        if($my_estate_Structure === "ＲＣ" || $my_estate_Structure === "ＳＲＣ"){
            $low_service_life = 47;
        }

        // 中古マンション取得時の耐用年数を取得する。
        if($low_service_life < $my_estate_elapsed_year ){
            $my_estate_get_service_life = $low_service_life * 0.2;

        } elseif($low_service_life > $my_estate_elapsed_year){
            $my_estate_get_service_life = ( $low_service_life - $my_estate_elapsed_year) + $my_estate_elapsed_year * 0.2;
        }

        // 償却率表（depreciation_rate.json）から値を取り出す
        $url = resource_path("data/tax/depreciation_rate.json");
        $json = file_get_contents($url);
        $depreciation_rate_arrays = json_decode($json, true);

        // 償却率表と中古マンション取得時の耐用年数から償却率を取得する。
        foreach($depreciation_rate_arrays["new_depreciation_rate"] as $depreciation_rate_array){

            if($depreciation_rate_array["service_life"] === intval($my_estate_get_service_life)){

                $my_estate_depreciation_rate = $depreciation_rate_array["rate"];
            }
        }

        // 物件取得価格と固定資産評価額（土地）と固定資産評価額（家屋）から建物のみの物件取得価格を算出する
        $my_estate_buy_price_house = $my_estate_buy_price * $my_estate_property_std_house_price / ($my_estate_property_std_land_price + $my_estate_property_std_house_price);

        // 減価償却費用を算出する
        $my_estate_depreciation_price = $my_estate_buy_price_house * $my_estate_depreciation_rate;
        
        // dd("my_estate_depreciation_price",$my_estate_depreciation_price,"my_estate_get_service_life",$my_estate_get_service_life);

        return view("estate.strategy_output",[

            "my_estate_depreciation_price" => $my_estate_depreciation_price,
            "my_estate_get_service_life" => $my_estate_get_service_life,
        ]);
    }

    function strategyOutputSetTime(Request $request){

        // strategyOutputShowから値を取り出す
        $input = $this->strategyOutputShow($request);
        $input_buy = $input["input_buy"];

        $date = $input_buy["loan_repayment_start_year"] . "-" . $input_buy["loan_repayment_start_month"];
        $loan_repayment_start_age = $input_buy["loan_repayment_start_age"];
        $timestamp = strtotime($date);
        $loan_repayment_start_year = $input_buy["loan_repayment_start_year"];
        $loan_repayment_start_month = $input_buy["loan_repayment_start_month"];

        return [
            "timestamp" => $timestamp,
            "loan_repayment_start_age" => $loan_repayment_start_age,
            "loan_repayment_start_year" => $loan_repayment_start_year,
            "loan_repayment_start_month" => $loan_repayment_start_month,
        ];
    }


    function strategyOutputBuyCalc(Request $request){

        // strategyOutputShowから値を取り出す
        $input = $this->strategyOutputShow($request);
        $input_buy = $input["input_buy"];

        // strategyOutputGetMyestateから値を取り出す
        $my_estate = $this->strategyOutputGetMyestate($request);
        $my_estate_buy_price = $my_estate["my_estate_buy_price"];

        // 変数化（in）する
        $loan = ($input_buy["buy_price"] - $input_buy["loan_own_resource"]) * 10000;
        $loan_own_resource = $input_buy["loan_own_resource"] * 10000;
        $loan_repayment_method = $input_buy["loan_repayment_method"];
        $loan_repayment_duration = $input_buy["loan_repayment_duration"];
        $loan_repayment_start_year = $input_buy["loan_repayment_start_year"];
        $loan_repayment_start_month = $input_buy["loan_repayment_start_month"];
        $loan_repayment_start_age = $input_buy["loan_repayment_start_age"];
        $loan_interest_rate_method = $input_buy["loan_interest_rate_method"];

        // 変数化（out）する
        $buy_brokerage_rate = $input_buy["buy_brokerage_rate"] * 0.01;
        $buy_stamp_fee = $input_buy["buy_stamp_fee"];
        $buy_registration_fee = $input_buy["buy_registration_fee"];
        $buy_other_fee = $input_buy["buy_other_fee"];

        // 購入CF（in）を計算する
        $buy_CF_in = $loan  + $loan_own_resource;

        // 購入CF（out）を計算する
        $buy_CF_out = $my_estate_buy_price * (1 + $buy_brokerage_rate) + $buy_stamp_fee + $buy_registration_fee + $buy_other_fee;
    
        // 購入CF（in - out）を計算する
        $buy_CF = $buy_CF_in - $buy_CF_out;

        return [
            "buy_CF" => $buy_CF,
            "loan_repayment_duration" => $loan_repayment_duration,
            "loan_repayment_start_year" => $loan_repayment_start_year,
            "loan_repayment_start_month" => $loan_repayment_start_month,
        ];
    }
    
    function strategyOutputPossessCalcIncome(Request $request){

        // strategyOutputShowから値を取り出す
        $input = $this->strategyOutputShow($request);
        $input_possess = $input["input_possess"];
        $fire_insurance = intval($input_possess["fire_insurance"]);
        $erthquake_insurance = intval($input_possess["erthquake_insurance"]);
        $other_insurance = intval($input_possess["other_insurance"]);
        $property_possess_expense = intval($input_possess["property_possess_expense"]);

        // strategyOutputPossessCalcDepreciationから値を取り出す
        $my_estate_depreciation = $this->strategyOutputPossessCalcDepreciation($request);
        $my_estate_depreciation_price = $my_estate_depreciation["my_estate_depreciation_price"];
        $my_estate_get_service_life = $my_estate_depreciation["my_estate_get_service_life"];

        // strategyOutputGetMyestateからマイ物件を取得する
        $my_estate = $this->strategyOutputGetMyestate($request);
        $my_estate_property_income = $my_estate["my_estate_property_income"];//家賃収入の変動処理は一旦保留にする
        $my_estate_property_management_cost = $my_estate["my_estate_property_management_cost"];
        $my_estate_property_maintenance_cost = $my_estate["my_estate_property_maintenance_cost"];
        $my_estate_property_tax = $my_estate["my_estate_property_tax"];
        $my_estate_city_plan_tax = $my_estate["my_estate_city_plan_tax"];
        

        // strategyOutputBuyCalcから値を取り出す
        $input_buy = $this->strategyOutputBuyCalc($request);
        $loan_repayment_duration = $input_buy["loan_repayment_duration"];

        // strategyOutputSetTimeから値を取り出す
        $set_time = $this->strategyOutputSetTime($request);
        $timestamp = $set_time["timestamp"];
        $loan_repayment_start_age = $set_time["loan_repayment_start_age"];
        $loan_repayment_start_year = $set_time["loan_repayment_start_year"];
        $loan_repayment_start_month = $set_time["loan_repayment_start_month"];

        // 不動産収入を計算する
        $total_my_estate_property_income_year = $my_estate_property_income * 12;

        // 不動産収入のための経費を取得する
        // ローン金利
        $arrays_interest_payment = array();
        $arrays_interest_payment_year = array();

        for ($i = 0; $i < $loan_repayment_duration; $i++){

            for ($j = 0; $j < 12; $j++){

                $arrays_interest_payment[$i][$j] = $request->session()->get($i)[$j]["interest_payment"];
            }

            // 年間のローン返済額を算出する
            $arrays_interest_payment_year[$i] = array_sum($arrays_interest_payment[$i]);
        }

        // マイ物件の管理費
        $total_my_estate_property_management_cost_year = $my_estate_property_management_cost * 12;

        // マイ物件の修繕積立費
        $total_my_estate_property_maintenance_cost_year = $my_estate_property_maintenance_cost * 12;

        // 火災保険料、地震保険料、固定資産税、都市計画税は年額なので上記で取得する

        // 減価償却費は年額なので上記で取得する


        // 不動産収入、経費、不動産所得を配列に格納し、所得税を計算する
        $calc_arrays_property_income = array();

        // 不動産収入、経費、不動産所得を配列に格納する
        for ($i = 0; $i < $loan_repayment_duration; $i++){

            // 家賃収入を格納する
            $calc_arrays_property_income[$i]["total_my_estate_property_income_year"] = $total_my_estate_property_income_year;

            // 各経費を$calc_arrays_property_incomeに格納する
            $calc_arrays_property_income[$i]["total_my_estate_property_management_cost_year"] = $total_my_estate_property_management_cost_year;
            $calc_arrays_property_income[$i]["total_my_estate_property_maintenance_cost_year"] = $total_my_estate_property_maintenance_cost_year;
            
            $calc_arrays_property_income[$i]["property_tax"] = $my_estate_property_tax;
            $calc_arrays_property_income[$i]["city_plan_tax"] = $my_estate_city_plan_tax;
            $calc_arrays_property_income[$i]["fire_insurance"] = $fire_insurance;
            $calc_arrays_property_income[$i]["erthquake_insurance"] = $erthquake_insurance;
            $calc_arrays_property_income[$i]["other_insurance"] = $other_insurance;
            $calc_arrays_property_income[$i]["property_possess_expense"] = $property_possess_expense;
            $calc_arrays_property_income[$i]["interest_payment_year"] = $arrays_interest_payment_year[$i];

            // 減価償却費を経費計上できる場合（i+1が耐用年数より大きくなると減価償却費を経費計上できなくなる）
            if ($i < $my_estate_get_service_life) {
                $calc_arrays_property_income[$i]["depreciation_price"] = $my_estate_depreciation_price;
            } else {
                $calc_arrays_property_income[$i]["depreciation_price"] = null;
            }

            // 不動産所得を計算する
            $calc_arrays_property_income[$i]["real_estate_income_year"] = 
            $calc_arrays_property_income[$i]["total_my_estate_property_income_year"] - 
            (
                $calc_arrays_property_income[$i]["total_my_estate_property_management_cost_year"]+
                $calc_arrays_property_income[$i]["total_my_estate_property_maintenance_cost_year"]+
                $calc_arrays_property_income[$i]["property_tax"]+
                $calc_arrays_property_income[$i]["city_plan_tax"]+
                $calc_arrays_property_income[$i]["fire_insurance"]+
                $calc_arrays_property_income[$i]["erthquake_insurance"]+
                $calc_arrays_property_income[$i]["other_insurance"]+
                $calc_arrays_property_income[$i]["property_possess_expense"]+
                $calc_arrays_property_income[$i]["interest_payment_year"]+
                $calc_arrays_property_income[$i]["depreciation_price"]
            );
        }

        // dd("calc_arrays_property_income",$calc_arrays_property_income);

        return view("estate.strategy_output",[
            "calc_arrays_property_income" => $calc_arrays_property_income ?? [],

        ]);
    }

    // function strategyOutputPossessCalcIncomeResidentTax(Request $request){

    //     // strategyOutputPossessCalcIncomeから値を取得する
    //     $calc_arrays_property_income = $this->strategyOutputPossessCalcIncome($request);
    //     $calc_arrays_property_income = $calc_arrays_property_income["calc_arrays_property_income"];

    //     // strategyOutputBuyCalcから値を取り出す
    //     $input_buy = $this->strategyOutputBuyCalc($request);
    //     $loan_repayment_duration = $input_buy["loan_repayment_duration"];

    //     // 不動産所得から所得税を計算する。課税される所得金額に応じてif文を作成する
    //     $calc_arrays_property_income_resident_tax = array();

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         // 不動産所得が1,000円～1,950,000円の場合
    //         if($calc_arrays_property_income[$i]["real_estate_income_year"] < 1950000){

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.05;

    //         // 不動産所得が1,950,000円～3,299,000円の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 1950000 && $calc_arrays_property_income[$i]["real_estate_income_year"] < 3300000) {

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.1 - 97500;

    //         // 不動産所得が3,300,000円～6,949,000円の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 3300000 && $calc_arrays_property_income[$i]["real_estate_income_year"] < 6950000){

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.2 - 427500;

    //         // 不動産所得が6,950,000円～8,999,000円の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 6950000 && $calc_arrays_property_income[$i]["real_estate_income_year"] < 9000000){

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.23 - 636000;

    //         // 不動産所得が9,000,000円～17,999,000円の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 9000000 && $calc_arrays_property_income[$i]["real_estate_income_year"] < 18000000) {

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.33 - 1536000;

    //         // 不動産所得が18,000,000円～39,999,000円の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 18000000 && $calc_arrays_property_income[$i]["real_estate_income_year"] < 40000000) {

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.40 - 2796000;

    //         // 不動産所得が40,000,000円以上の場合
    //         } elseif($calc_arrays_property_income[$i]["real_estate_income_year"] >= 40000000){

    //             // 所得税を計算する
    //             $calc_arrays_property_income_resident_tax[$i]["property_income_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.45 - 4796000;

    //         }

    //         // 住民税を計算する
    //         $calc_arrays_property_income_resident_tax[$i]["property_resident_tax"] = $calc_arrays_property_income[$i]["real_estate_income_year"] * 0.1;

    //     }

    //     // dd("calc_arrays_property_income_resident_tax",$calc_arrays_property_income_resident_tax);

    //     return [
    //         "calc_arrays_property_income_resident_tax" => $calc_arrays_property_income_resident_tax ?? [],

    //     ];
    // }

    // function strategyOutputPossessCalc(Request $request){

    //     // strategyOutputShowから値を取り出す
    //     $input = $this->strategyOutputShow($request);
    //     $input_possess = $input["input_possess"];

    //     // strategyOutputBuyCalcから値を取り出す
    //     $input_buy = $this->strategyOutputBuyCalc($request);
    //     $loan_repayment_duration = $input_buy["loan_repayment_duration"];
    //     $loan_repayment_start_year = $input_buy["loan_repayment_start_year"];
    //     $loan_repayment_start_month = $input_buy["loan_repayment_start_month"];

    //     // strategyOutputSetTimeから値を取り出す
    //     $set_time = $this->strategyOutputSetTime($request);
    //     $timestamp = $set_time["timestamp"];
    //     $loan_repayment_start_age = $set_time["loan_repayment_start_age"];
    //     $loan_repayment_start_year = $set_time["loan_repayment_start_year"];
    //     $loan_repayment_start_month = $set_time["loan_repayment_start_month"];

    //     // strategyOutputGetMyestateから値を取り出す（固定資産税、都市計画税）
    //     $my_estate = $this->strategyOutputGetMyestate($request);
    //     $my_estate_property_management_cost = $my_estate["my_estate_property_management_cost"];
    //     $my_estate_property_maintenance_cost = $my_estate["my_estate_property_maintenance_cost"];
    //     $my_estate_property_tax = $my_estate["my_estate_property_tax"];
    //     $my_estate_city_plan_tax = $my_estate["my_estate_city_plan_tax"];
        

    //     // strategyOutputPossessCalcIncomeResidentTaxから値を取り出す（所得税、住民税）
    //     $calc_arrays_property_income_resident_tax = $this->strategyOutputPossessCalcIncomeResidentTax($request);

    //     // dd($timestamp,$loan_repayment_start_age);
    //     // dd( $timestamp,
    //     //     date("Y-m",$timestamp),
    //     //     date("n",$timestamp),
    //     //     date("Y/m",strtotime("+13 month",$timestamp)),
    //     // );


    //     // 変数化（in）する
    //     $property_income = $input_possess["property_income"];//家賃収入（円/月）
    //     $refund = $input_possess["refund"];//還付金（円/年）※確定申告後、4月末に振り込まれる

    //     // 変数化（out-物件保有）する
    //     //上記のstrategyOutputGetMyestateから値を取り出す部分で変数化済み。管理費（円/月）
    //     //上記のstrategyOutputGetMyestateから値を取り出す部分で変数化済み。修繕積立費（円/月）
    //     // ※毎月ローン返済は別途セッションを使って格納する。

    //     // 変数化（out-税）する
    //     //上記のstrategyOutputGetMyestateから値を取り出す部分で変数化済み。（仮）固定資産税（円/年）※6月末、9月末、12月末、翌年2月末。
    //     //上記のstrategyOutputGetMyestateから値を取り出す部分で変数化済み。（仮）都市計画税（円/年）※6月末、9月末、12月末、翌年2月末
    //     //上記のstrategyOutputPossessCalcIncomeResidentTaxから値を取り出す部分で変数化済み。（仮）所得税（円/年）※3月末
    //     //上記のstrategyOutputPossessCalcIncomeResidentTaxから値を取り出す部分で変数化済み。（仮）住民税（円/年）※5月末

    //     // 変数化（out-保険）する
    //     $fire_insurance = $input_possess["fire_insurance"];//年間火災保険料（円/年）※年に1回
    //     $erthquake_insurance = $input_possess["erthquake_insurance"];//年間地震保険料（円/年）※5年に1回支払いだが、とりあえず、フォームに合わせて年1回支払いにする
    //     $other_insurance = $input_possess["other_insurance"];//年間その他保険料（円/年）※年に1回

    //     // 変数化（out-その他）する
    //     $repair_cost = $input_possess["repair_cost"];//個別修繕費（円/回）※ローンの返済開始月に発生するものとする。
    //     $repair_start = intval($input_possess["repair_start"]);//個別修繕開始年
    //     $repair_frequency = intval($input_possess["repair_frequency"]);//上記が発生する頻度
    //     $eviction_cost = $input_possess["eviction_cost"];//立ち退き費用（円/回）※ローンの返済開始月に発生するものとする。
    //     $eviction_start = intval($input_possess["eviction_start"]);//立ち退きの開始年
    //     $eviction_frequency = intval($input_possess["eviction_frequency"]);//立ち退きが発生する頻度
    //     $other_cost = $input_possess["other_cost"];//その他（円/回）※ローンの返済開始月に発生するものとする。
    //     $other_start = intval($input_possess["other_start"]);//その他の開始年
    //     $other_frequency = intval($input_possess["other_frequency"]);//その他が発生する頻度

    //     /**
    //      * ↓↓↓リファクタリングで別の関数かクラスにしてもいいかも。↓↓↓
    //      */
    //     // 保有CFを計算する

    //     $calc_arrays = array();

    //     // ローン返済期間（年）分のループを回す
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         // 12か月分のループを回す
    //         for ($j = 0; $j < 12; $j++){

    //             $calc_arrays[$i][$j] = [
    //                 "loan_repayment_schedule" => date("Y-n",strtotime("+$j month",$timestamp)),
    //                 "year" => date("Y",strtotime("+$j month",$timestamp)),
    //                 "month" => date("n",strtotime("+$j month",$timestamp)),
    //                 "loan_repayment_start_age" => $loan_repayment_start_age + $i,
    //             ];
    //         }
    //         $timestamp = strtotime($loan_repayment_start_year + $i + 1 . "-" . $loan_repayment_start_month);
    //     }

    //     // dd($calc_arrays);

    //     // 保有CF（in）を格納する
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($j = 0; $j < 12; $j++){

    //             // 家賃収入を格納する
    //             $calc_arrays[$i][$j]["property_income"] = $property_income;

    //             // 4月の場合
    //             if(date("n",strtotime("+$j month",$timestamp)) === "4"){
    //                 // 還付金を格納する
    //                 $calc_arrays[$i][$j]["refund"] = $refund;

    //             } else {
    //                 // 還付金を格納しない
    //                 $calc_arrays[$i][$j]["refund"] = null;
    //             }
    //         }
    //     }

    //     // 保有CF（out-物件保有）を格納する
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($j = 0; $j < 12; $j++){

    //             // 管理費、修繕積立費、月間ローン返済額を格納する
    //             $calc_arrays[$i][$j]["property_management_cost"] = $my_estate_property_management_cost;
    //             $calc_arrays[$i][$j]["property_maintenance_cost"] = $my_estate_property_maintenance_cost;
    //             // 月間ローン返済額は別途calc_arraysに追加する
    //         }
    //     }

    //     // dd($calc_arrays);

    //     // 保有CF（out-税）を格納する
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($j = 0; $j < 12; $j++){

    //             $calc_arrays[$i][$j]["property_tax"] = null;
    //             $calc_arrays[$i][$j]["city_plan_tax"] = null;
    //             $calc_arrays[$i][$j]["property_income_tax"] = null;
    //             $calc_arrays[$i][$j]["property_resident_tax"] = null;

    //             // 6月、9月、12月、翌年2月の場合
    //             if(
    //                 intval(date("n",strtotime("+$j month",$timestamp))) === 6 || 
    //                 intval(date("n",strtotime("+$j month",$timestamp))) === 9 || 
    //                 intval(date("n",strtotime("+$j month",$timestamp))) === 12 ||
    //                 intval(date("n",strtotime("+$j month",$timestamp))) === 2
    //             ) {

    //                 // 固定資産税、都市計画税を格納する
    //                 $calc_arrays[$i][$j]["property_tax"] = $my_estate_property_tax / 4;
    //                 $calc_arrays[$i][$j]["city_plan_tax"] = $my_estate_city_plan_tax / 4;

    //             // 3月の場合
    //             } elseif(intval(date("n",strtotime("+$j month",$timestamp))) === 3) {

    //                 // 所得税を格納する
    //                 $calc_arrays[$i][$j]["property_income_tax"] = $calc_arrays_property_income_resident_tax["calc_arrays_property_income_resident_tax"][$i]["property_income_tax"];


    //             // 5月の場合
    //             } elseif(intval(date("n",strtotime("+$j month",$timestamp))) === 5)  {

    //                 // 住民税を格納する
    //                 $calc_arrays[$i][$j]["property_resident_tax"] = $calc_arrays_property_income_resident_tax["calc_arrays_property_income_resident_tax"][$i]["property_resident_tax"];

    //             } 
    //         }
    //     }


    //     // 保有CF（out-保険）を格納する
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($j = 0; $j < 12; $j++){

    //             // 各保険の支払いをローンの返済開始（月）に合わせる
    //             if(date("n",strtotime("+$j month",$timestamp)) === $loan_repayment_start_month) {

    //                 $calc_arrays[$i][$j]["fire_insurance"] = $fire_insurance;
    //                 $calc_arrays[$i][$j]["erthquake_insurance"] = $erthquake_insurance;
    //                 $calc_arrays[$i][$j]["other_insurance"] = $other_insurance;

    //             } else {
    //                 $calc_arrays[$i][$j]["fire_insurance"] = null;
    //                 $calc_arrays[$i][$j]["erthquake_insurance"] = null;
    //                 $calc_arrays[$i][$j]["other_insurance"] = null;
    //             }
    //         }

    //     }



    //     // 保有CF（out-その他-個別修繕）を格納する
    //     // ローン返済期間中の個別修繕を実施する回数を算出する
    //     $count_repair = ($loan_repayment_start_year + $loan_repayment_duration - $repair_start) / $repair_frequency;

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){
    //         for ($j = 0; $j < 12; $j++){
    //             $calc_arrays[$i][$j]["repair_cost"] = null;
    //         }
    //     }

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($k = 0; $k < $count_repair; $k++){

    //             // 以下の$test_arrays[$j]は、個別修繕の実施年を格納している。
    //             // $test_arrays[$k] = $repair_start + $repair_frequency * $k;

    //             // 個別修繕を実施する年の場合
    //             if(intval(date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]))) === $repair_start + $repair_frequency * $k) {

    //                 // $test_arrays[$i]["test"] = date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]));

    //                 for ($j = 0; $j < 12; $j++){

    //                     // ローン開始月の場合
    //                     if(date("n",strtotime("+$j month",$timestamp)) === $loan_repayment_start_month) {

    //                         // 個別修繕の費用を格納する。
    //                         $calc_arrays[$i][$j]["repair_cost"] = $repair_cost;

    //                     } else {

    //                         $calc_arrays[$i][$j]["repair_cost"] = null;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // 保有CF（out-その他-立ち退き）を格納する
    //     // ローン返済期間中の立ち退きを実施する回数を算出する
    //     $count_eviction = ($loan_repayment_start_year + $loan_repayment_duration - $eviction_start) / $eviction_frequency;

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){
    //         for ($j = 0; $j < 12; $j++){
    //             $calc_arrays[$i][$j]["eviction_cost"] = null;
    //         }
    //     }

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($k = 0; $k < $count_eviction; $k++){

    //             // 以下の$test_arrays[$j]は、個別修繕の実施年を格納している。
    //             // $test_arrays[$k] = $eviction_start + $eviction_frequency * $k;

    //             // 個別修繕を実施する年の場合
    //             if(intval(date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]))) === $eviction_start + $eviction_frequency * $k) {

    //                 // $test_arrays[$i]["test"] = date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]));

    //                 for ($j = 0; $j < 12; $j++){

    //                     // ローン開始月の場合
    //                     if(date("n",strtotime("+$j month",$timestamp)) === $loan_repayment_start_month) {

    //                         // 個別修繕の費用を格納する。
    //                         $calc_arrays[$i][$j]["eviction_cost"] = $eviction_cost;

    //                     } else {

    //                         $calc_arrays[$i][$j]["eviction_cost"] = null;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // 保有CF（out-その他-その他）を格納する
    //     // ローン返済期間中のその他を実施する回数を算出する
    //     $count_other = ($loan_repayment_start_year + $loan_repayment_duration - $other_start) / $other_frequency;

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){
    //         for ($j = 0; $j < 12; $j++){
    //             $calc_arrays[$i][$j]["other_cost"] = null;
    //         }
    //     }

    //     for ($i = 0; $i < $loan_repayment_duration; $i++){

    //         for ($k = 0; $k < $count_other; $k++){

    //             // 以下の$test_arrays[$j]は、個別修繕の実施年を格納している。
    //             // $test_arrays[$k] = $other_start + $other_frequency * $k;

    //             // 個別修繕を実施する年の場合
    //             if(intval(date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]))) === $other_start + $other_frequency * $k) {

    //                 // $test_arrays[$i]["test"] = date("Y",strtotime($calc_arrays[$i][0]["loan_repayment_schedule"]));

    //                 for ($j = 0; $j < 12; $j++){

    //                     // ローン開始月の場合
    //                     if(date("n",strtotime("+$j month",$timestamp)) === $loan_repayment_start_month) {

    //                         // 個別修繕の費用を格納する。
    //                         $calc_arrays[$i][$j]["other_cost"] = $other_cost;

    //                     } else {

    //                         $calc_arrays[$i][$j]["other_cost"] = null;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // ほかの関数で作成したcalc_arrayと統合する
    //     for ($i = 0; $i < $loan_repayment_duration; $i++){
    //         for ($j = 0; $j < 12; $j++){

    //             // calc_arrays_loan@BuyControllerを格納する
    //             $calc_arrays[$i][$j]["loan_repayment_month"] = $request->session()->get($i)[$j]["loan_repayment_month"];
    //             $calc_arrays[$i][$j]["interest_payment"] = $request->session()->get($i)[$j]["interest_payment"];
    //             $calc_arrays[$i][$j]["principal_repayment"] = $request->session()->get($i)[$j]["principal_repayment"];
    //             $calc_arrays[$i][$j]["loan_remain"] = $request->session()->get($i)[$j]["loan_remain"];
    //             $calc_arrays[$i][$j]["total_interest_payment"] = $request->session()->get($i)[$j]["total_interest_payment"];
    //             $calc_arrays[$i][$j]["total_repayment"] = $request->session()->get($i)[$j]["total_repayment"];
    //         }
    //     }


    //     dd("calc_arrays",$calc_arrays);

    //     /**
    //      * ↑↑↑リファクタリングで別の関数かクラスにしてもいいかも。↑↑↑
    //      */

    //     return view("estate.strategy_output",[
    //         "calc_arrays" => $calc_arrays ?? [],
    //         "loan_repayment_duration" => $loan_repayment_duration ?? [],

    //     ]);
    // }





    // function strategyOutputSellCalc(Request $request){

    //     // strategyOutputShowから値を取り出す
    //     $input = $this->strategyOutputShow($request);
    //     $input_sell = $input["input_sell"];

    //     // 変数化（in）する
    //     // ※将来的には、$KARI_SELL_PRICEを相場予測できるようにしたい。
    //     $KARI_SELL_PRICE = $input_sell["KARI_SELL_PRICE"] * 10000;

    //     // 変数化（out）する
    //     $sell_fee_rate = $input_sell["sell_fee_rate"] * 0.01;
    //     $sell_stamp_fee = $input_sell["sell_stamp_fee"];
    //     $sell_other_fee = $input_sell["sell_other_fee"];

    //     // 売却CF（out）を計算する
    //     $sell_CF_out = $KARI_SELL_PRICE * $sell_fee_rate + $sell_stamp_fee + $sell_other_fee;
        
    //     $sell_CF = $KARI_SELL_PRICE - $sell_CF_out;

    //     return [
    //         "sell_CF" => $sell_CF,
    //     ];
    // }
}