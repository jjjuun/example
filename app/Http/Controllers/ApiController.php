<?php

namespace App\Http\Controllers;

use App\Estate\EstateFilter;
use App\Estate\FloorPlan;
use App\Models\Estate;
use App\Repository\EstateApiRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\VarDumper;
use Carbon\Carbon;

class ApiController extends Controller
{

    function __construct(EstateApiRepository $estateApi)
    {
        // ApiControllerクラスのestateApiプロパティにEstateRepositoryクラスを指定する。
        // EstateRepositoryクラスを使いたい時は、$this->estateApi->（EstateRepositoryクラス内の関数）と記述する
        $this->estateApi = $estateApi;

        // 上記１行を書くことで、__constructにより、$this->estateApi = new EstateApiRepository();の1行を書かなくて済む
        // また、EstateApiRepositoryクラスの__construct関数で別のクラス（EstateApiDriver）を引数として使用する場合は、ここで、以下のような記述をしなければならない。
        // $this->estateApi = new EstateApiResository(new EstateApiDriver());
        // ※さらに、EstateApiDriverで別のクラスを引数に使う時はどんどん連鎖していく。
        // Laravelは機能をバラバラにする（クラスや関数を複数指定する）ことを前提にコーディングするのがよい。
        

        // 依存解決（Dependency injection）＝必要な物は自動的に一気に全部持ってくる。
    }

    /**
     * /apiのフォーム画面を作る
     * form関数はApiControllerで完結している
     */
    function form()
    {

        // ログインアカウントのuser_idを使ってマイ物件を取得する
        $myEstates = Estate::where("user_id", "=", Auth::id())->get();

        // resources/data/prefectures.phpから都道府県の配列を取得する
        $prefectures = require resource_path("data/prefectures.php");
    
        return view("estate.api", [
            "thisYear" => date("Y"),
            "myEstates" => $myEstates,
            "prefectures" => $prefectures,
            "citys" => [],
            // prefectures.phpと同じ方法でもいい。FloorPlanはクラスを作る方法で別のオブジェクトで定義している。
            "FloorPlans" => FloorPlan::all()
        ]);
    }

    /**
     * フォームの入力内容に一致したものを取得したAPIから取り出す
     */
    function search(Request $request, EstateFilter $filter)
    {

        // フォームで取得した値をすべて受け取る
        $input = $request->all();

        // EstateRepositoryクラスsearch関数（引数：上記で定義した$input）を$estateとして定義する。
        // $estates = EstateRepositoryのsearch関数から渡ってくる$selected_estatesのようなイメージ
        $estates = $this->estateApi->search($input);
        
        // dd($estates);  //→  取引時期、都道府県（or市区町村）を選択した時のAPI配列がddされる。


        // マイ物件が入力されている場合。配列の最初は0。ifの条件に$input["myEstateDB"]があると、物件１を選んだ場合、$input["myEstateDB"]は"0"になるので、マイ物件を選択したことにならなくなってしまう。
        // なので、$input["myEstateDB"]はifの条件には使わない。
        if (isset($input["myEstateDB"])) {

            /**
             * ここで、"FloorPlan" => "2LDK","BuildingYear" => "平成15年"のような条件が配列として出力されるようにする。
             */

            // 論理削除していないログインユーザーのマイ物件を取得する
            $DB_selected_estates = Estate::where("DB_status","=",1)
            ->where("user_id", "=", Auth::id())
            ->get();

            $DB_selected_estates_arrays = [
                "Type" => $DB_selected_estates[$input["myEstateDB"]]["Type"],
                "Prefecture" => $DB_selected_estates[$input["myEstateDB"]]["Prefecture"],
                "Municipality" => $DB_selected_estates[$input["myEstateDB"]]["Municipality"],
                "DistrictName" => $DB_selected_estates[$input["myEstateDB"]]["DistrictName"],
                "FloorPlan" => $DB_selected_estates[$input["myEstateDB"]]["FloorPlan"],
                "BuildingYear" => $DB_selected_estates[$input["myEstateDB"]]["BuildingYear"]
            ];
            // dd($DB_selected_estates_arrays); //結果は以下の通り。
            /**マイ物件の時
             * array:6 [▼
             * "Type" => "中古マンション等"
             * "Prefecture" => "東京都"
             * "Municipality" => "港区"
             * "DistrictName" => "六本木"
             * "FloorPlan" => "２ＬＤＫ"
             * "BuildingYear" => "平成15年"
             * ]
             */
            // 

            foreach($DB_selected_estates_arrays as $key => $value){
                $filter->addMyEstate($key, $value);
            };
            /**マイ物件の時
             * -filters: array:6 [▼
             * "Type" => "中古マンション等"
             * "Prefecture" => "東京都"
             * "Municipality" => "千代田区"
             * "DistrictName" => "三番町"
             * "FloorPlan" => "２ＬＤＫ"
             * "BuildingYear" => "平成15年"
             * ]
             */

            /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
             * -filters: array:2 [▼
             * "FloorPlan" => "２ＬＤＫ"
             * "BuildingYear" => "平成15年"
             * ]
             */
            // 
            
            $new_selected_estates = $filter->filter($estates);
            // dd("new_selected_estates at マイ物件",$new_selected_estates);//->以下の通り。
            /**マイ物件の時
             * 0 => array:17 [▼
             * "Type" => "中古マンション等"
             * "MunicipalityCode" => "13101"
             * "Prefecture" => "東京都"
             * "Municipality" => "千代田区"
             * "DistrictName" => "三番町"
             * "TradePrice" => "190000000"
             * "FloorPlan" => "２ＬＤＫ"
             * "Area" => "85"
             * "BuildingYear" => "平成15年"
             * "Structure" => "ＲＣ"
             * "Use" => "住宅"
             * "Purpose" => "住宅"
             * "CityPlanning" => "第２種住居地域"
             * "CoverageRatio" => "80"
             * "FloorAreaRatio" => "500"
             * "Period" => "2022年第１四半期"
             * "Renovation" => "未改装"
             * ]
             */
        }


        // 上記if文を通過した結果、"FloorPlan" => "2LDK","BuildingYear" => "平成15年"のような条件がdd($filter)して出力されるようにする。

        // 以下は、マイ物件を選んでいない場合。
        // 現状、マイ物件を選んでいる選んでいない関係なく、フォームに入力されている条件分岐を通ることになる
        
        if(isset($input["FloorPlans"]) && $input["FloorPlans"]){

            // EstatesFilterクラスのadd関数（引数FloorPlanにフォームで入力した値を格納する）を使用する
            // $filter->add("$key", $value)となるので、以下の場合、"FloorPlan"がadd関数の$keyとなり、$input["FloorPlan"]（例：2LDK）が$valueとなる。
            $filter->add("FloorPlan", $input["FloorPlans"]);
            // dd("filter",$filter); //-> 以下の通り。
            /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
             * -filters: array:1 [▼
             * "FloorPlan" => "２ＬＤＫ"
             * ]
             */
        }

        if (isset($input["BuildingYear"]) && $input["BuildingYear"]) {
            $filter->add("BuildingYear", $input["BuildingYear"]);
        }

        if (isset($input["DistrictName"]) && $input["DistrictName"]) {
            $filter->add("DistrictName", $input["DistrictName"]);
            
        }

        // dd("filter",$filter);//->以下の通り。
        /**マイ物件の時
         * -filters: array:6 [▼
         * "Type" => "中古マンション等"
         * "Prefecture" => "東京都"
         * "Municipality" => "千代田区"
         * "DistrictName" => "三番町"
         * "FloorPlan" => "２ＬＤＫ"
         * "BuildingYear" => "平成15年"
         * ]
         */

        /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
         * -filters: array:2 [▼
         * "FloorPlan" => "２ＬＤＫ"
         * "BuildingYear" => "平成15年"
         * ]
         */
        // 
    
        //$new_selected_estatesにEstateFilterクラスのfilter関数（引数：$estates）を使用する。
        $new_selected_estates = $filter->filter($estates);
        // dd("FINAL_new_selected_estates",$new_selected_estates); //-> フォームの入力条件にあった物件がフィルタリングされて配列に格納される。以下の通り。
        /**マイ物件の時
         * array:1 [▼
         * 0 => array:17 [▼
         * "Type" => "中古マンション等"
         * "MunicipalityCode" => "13101"
         * "Prefecture" => "東京都"
         * "Municipality" => "千代田区"
         * "DistrictName" => "三番町"
         * "TradePrice" => "190000000"
         * "FloorPlan" => "２ＬＤＫ"
         * "Area" => "85"
         * "BuildingYear" => "平成15年"
         * "Structure" => "ＲＣ"
         * "Use" => "住宅"
         * "Purpose" => "住宅"
         * "CityPlanning" => "第２種住居地域"
         * "CoverageRatio" => "80"
         * "FloorAreaRatio" => "500"
         * "Period" => "2022年第１四半期"
         * "Renovation" => "未改装"
         * ]
         * ]
         */
        
        /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
         * array:8 [▼
         * 0 => array:17 [▶]
         * 1 => array:16 [▶]
         * 2 => array:17 [▶]
         * 3 => array:17 [▶]
         * 4 => array:17 [▶]
         * 5 => array:16 [▶]
         * 6 => array:17 [▶]
         * 7 => array:17 [▶]
         * ]
         *  
         * */ 
        // 
        
        // 以下のforeach文で新らに作成する配列を定義する
        $tradePrice = array();
        $area = array();

        // フォームで作成した$new_selected_estatesからtradePrice（価格）とarea（面積）を取得する
        foreach ($new_selected_estates as $new_selected_estate) {
            $tradePrice[] = $new_selected_estate["TradePrice"];
            $area[] = $new_selected_estate["Area"];
        }

        // m2単価（万円）を計算する
        $unitPrice = ($area) ? (array_sum($tradePrice) / array_sum($area)) / 10000 : 0;

        // 上記を小数点第一位に丸める
        $float_unitPrice = round($unitPrice, 1);
        // }

        /*
        return response()->json([
            
            "new_selected_estates" => $new_selected_estates ?? [],
            
            // 最終的にViewに出力したい変数
            "unitPrice" => $unitPrice ?? null,
            "float_unitPrice" => $float_unitPrice ?? null,

            
        ]);
        */

        return view("api_result", [
            
            "new_selected_estates" => $new_selected_estates ?? [],
            
            // 最終的にViewに出力したい変数
            "unitPrice" => $unitPrice ?? null,
            "float_unitPrice" => $float_unitPrice ?? null,

            
        ]);
    }

    function getCitys(Request $request){

        // 入力フォームのarea（都道府県）を取得する。
        $area = $request->input("area");

        // resource_path：resourcesディレクトリへのパスを取得。
        // 例：フォームで東京都を選択した時（area=13）→"resources\data/city/13.json"というディレクトリを変数に格納する。
        $resource_path = resource_path("data/city/".sprintf("%02d",$area).".json");

        // file_get_contentsでディレクトリのファイルにアクセスし表示する。
        return response(file_get_contents($resource_path));
    }

    function getDistrictName(Request $request){

        // 入力フォームのarea（都道府県）を取得する。
        $citys = $request->input("citys");

        // resource_path：resourcesディレクトリへのパスを取得。
        // 例：フォームで東京都を選択した時（area=13）→"resources\data/city/13.json"というディレクトリを変数に格納する。
        $resource_path = resource_path("data/DistrictName/".$citys.".json");


        // file_get_contentsでディレクトリのファイルにアクセスし表示する。
        return response(file_get_contents($resource_path));
    }
}
