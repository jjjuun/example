<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
{

    /**
     * 作成フォーム
     */
    function form(Request $request){

        $input = $request->session()->get("estate_input");

        return view("estate.form", [
            "input" => $input ?? [],
            "prefectures" =>  [
                '1' => '北海道',
                '2' => '青森県',
                '3' => '岩手県',
                '4' => '宮城県',
                '5' => '秋田県',
                '6' => '山形県',
                '7' => '福島県',
                '8' => '茨城県',
                '9' => '栃木県',
                '10' => '群馬県',
                '11' => '埼玉県',
                '12' => '千葉県',
                '13' => '東京都',
                '14' => '神奈川県',
                '15' => '新潟県',
                '16' => '富山県',
                '17' => '石川県',
                '18' => '福井県',
                '19' => '山梨県',
                '20' => '長野県',
                '21' => '岐阜県',
                '22' => '静岡県',
                '23' => '愛知県',
                '24' => '三重県',
                '25' => '滋賀県',
                '26' => '京都府',
                '27' => '大阪府',
                '28' => '兵庫県',
                '29' => '奈良県',
                '30' => '和歌山県',
                '31' => '鳥取県',
                '32' => '島根県',
                '33' => '岡山県',
                '34' => '広島県',
                '35' => '山口県',
                '36' => '徳島県',
                '37' => '香川県',
                '38' => '愛媛県',
                '39' => '高知県',
                '40' => '福岡県',
                '41' => '佐賀県',
                '42' => '長崎県',
                '43' => '熊本県',
                '44' => '大分県',
                '45' => '宮崎県',
                '46' => '鹿児島県',
                '47' => '沖縄県',
            ] ??[],
            "Municipalitys" => [
                '13101' => '千代田区',
                '13102' => '中央区',
                '13103' => '港区',
                '13104' => '新宿区',
                '13105' => '文京区',
                '13106' => '台東区',
                '13107' => '墨田区',
                '13108' => '江東区',
                '13109' => '品川区',
                '13110' => '目黒区',
                '13111' => '大田区',
                '13112' => '世田谷区',
                '13113' => '渋谷区',
                '13114' => '中野区',
                '13115' => '杉並区',
                '13116' => '豊島区',
                '13117' => '北区',
                '13118' => '荒川区',
                '13119' => '板橋区',
                '13120' => '練馬区',
                '13121' => '足立区',
                '13122' => '葛飾区',
                '13123' => '江戸川区',
            ] ?? [],
            "Types" => [
                '1' => '中古マンション等',
                '2' => '宅地（土地と建物）',
                '3' => '宅地（土地）',
            ] ?? [],
            "FloorPlans" => [
                '1' => 'ワンルーム',
                '12' => '１Ｋ',
                '13' => '１ＤＫ',
                '14' => '１ＬＫ',
                '15' => '１ＬＤＫ',
                '22' => '２Ｋ',
                '23' => '２ＤＫ',
                '24' => '２ＬＫ',
                '25' => '２ＬＤＫ',
                '32' => '３Ｋ',
                '33' => '３ＤＫ',
                '34' => '３ＬＫ',
                '35' => '３ＬＤＫ',
                '42' => '４Ｋ',
                '43' => '４ＤＫ',
                '44' => '４ＬＫ',
                '45' => '４ＬＤＫ',
            ] ?? [],
        ]);
    }

    function check(Request $request){

        // フォームに入力された値をすべて取得する
        $input = $request->all();

        // バリデーション設定（今のところ特に設定しない）

        //セッションに書き込む（key名：estate_input）
        $request->session()->put("estate_input", $input);
        
        return redirect()->route("estate.create.confirm");
    }

    /**
     * 確認画面
     */
    function confirm(Request $request){

        $input = $request->session()->get("estate_input");

        // セッションに値がない時はフォームに戻る
        if(!$input){
            return redirect()->route("estate.create");
        }

        return view("estate.create.confirm",["input" => $input]);
    }

    
    function store(Request $request){

        if($request->input("back")){
            return redirect()->route("estate.create");
        }



        // セッションを取り出す
        $input = $request->session()->get("estate_input");

        // セッションに値が無い時はフォームに戻る
        if(!$input){
            return redirect()->route("estate.create");
        }

        // データベースに格納する変数を指定する
        // key名と同じ名前で変数を指定し、input配列の各keyから値を取り出す。
        $user_id = Auth::id();
        $EstateName = $input["EstateName"];
        $Type = $input["Type"];
        $Prefecture = $input["Prefecture"];
        $Municipality = $input["Municipality"];
        $DistrictName = $input["DistrictName"];
        $FloorPlan = $input["FloorPlan"];
        $Structure = $input["Structure"];
        $BuildingYear = $input["BuildingYear"];
        $GetYear = $input["GetYear"];
        $BuyPrice = $input["BuyPrice"];
        $property_income = $input["property_income"];//家賃収入
        $property_management_cost = $input["property_management_cost"];//管理費
        $property_maintenance_cost = $input["property_maintenance_cost"];//修繕積立費
        $property_tax = $input["property_tax"];//固定資産税
        $city_plan_tax = $input["city_plan_tax"];//都市計画税
        $property_std_land_price = $input["property_std_land_price"];//固定資産標準額（土地）
        $property_std_house_price = $input["property_std_house_price"];//固定資産標準額（家屋）

        $estate = new Estate();
        $estate->user_id = $user_id;
        $estate->EstateName = $EstateName;
        $estate->Type = $Type;
        $estate->Prefecture = $Prefecture;
        $estate->Municipality = $Municipality;
        $estate->DistrictName = $DistrictName;
        $estate->FloorPlan = $FloorPlan;
        $estate->Structure = $Structure;
        $estate->BuildingYear = $BuildingYear;
        $estate->GetYear = $GetYear;
        $estate->BuyPrice = $BuyPrice;
        $estate->property_income = $property_income;
        $estate->property_management_cost = $property_management_cost;
        $estate->property_maintenance_cost = $property_maintenance_cost;
        $estate->property_tax = $property_tax;
        $estate->city_plan_tax = $city_plan_tax;
        $estate->property_std_land_price = $property_std_land_price;
        $estate->property_std_house_price = $property_std_house_price;
        $estate->save();

        $request->session()->forget("estate_input");

        return redirect()->route("estate.index");
    }

}
