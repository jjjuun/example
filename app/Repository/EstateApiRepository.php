<?php
namespace App\Repository;

use Illuminate\Support\Facades\Http;

class EstateApiRepository {
    /**
     * このクラスでは、以下の機能を実装する。
     * buildApiUrl：フォームの入力値をもとにAPIで検索するためのurlを作成し返す
     * search：取得したAPIからFloorPlanとBuildingYearが格納されている物を選択し、$selected_estatesを作成し返す
     */

    function __construct()
    {
        // 以下のように、この__construct関数で、別のクラスを引数として使用する場合、ApiControllerでクラスを呼び出す記述がさらに必要になる。
        // function __construct(EstateApiDriver $driver)
    }

    /**
     * 入力項目を受け取り、検索用のURLを組み立てる
     */
    function buildApiUrl($input){

        $search_query = [
            "from" => $input["from"] . $input["from_month"],
            "to" => $input["to"] . $input["to_month"],
        ];

        if(isset($input["citys"]) && $input["citys"]){

            $search_query["city"] = $input["citys"];

        }else if(isset($input["prefectures"]) && $input["prefectures"]){

            // sprintfで都道府県番号が一桁の番号の場合、0を入力する。例：1→01。
            $search_query["area"] = sprintf("%02d", $input["prefectures"]);
        }


        $url = "https://www.land.mlit.go.jp/webland/api/TradeListSearch?" . http_build_query($search_query);
        //dd($url);
        return $url;

        // $url = "https://www.land.mlit.go.jp/webland/api/CitySearch?area=14";

    }

    /**
     * 取得したAPIから検索条件に一致したものを$selected_estatesとして返す（returnする）
     */
    function search($input){

        /**
         * 第一段階のリファクタリングとしては、以下のコードをApiControllerからEstateApiRepositoryに持ってくることになる。
         * $serch_query = [
         *      "from" => $input["from"] . $input["from_month"],
         *      "to" => $input["to"] . $input["to_month"],
         * ];
         * 
         * if($request->input("citys")){
         *      $serch_query["city"] = input["citys"];
         * } elseif($request->input("prefectures")){
         *      $serch_Query["area"] = $input["prefectures"];
         * }
         * 
         * $url = "https://www.land.mlit.go.jp/webland/api/TradeListSearch?" . http_build_query($search_query);
         * 
         * しかし、上記はテンプレート化できるので、さらにリファクタリングが可能。
         * そのため、$url = $this->buildApiUrl($input);と記述して、buildApiUrl関数で作ったurlを使用する
         * 
         */

        // fromとtoが指定されていないと、API取得できないので、以下のif文は不要だと思われる。
        if(!isset($input["from"])){
            return [];
        }

        // buildApiUrl関数で作った$urlを取得する
        $url = $this->buildApiUrl($input);

        // $urlからAPIを取得する
        $responses = Http::get($url);

        // タイムアウトする時にエラーが出る？？
        $responses->throw();

        // FloorPlanとBuildingYearが格納されている物を格納するための配列を作成する
        $selected_estates = array();

        // 取得したAPIからFloorPlanとBuildingYearが格納されている物を選択し、配列selected_estatesに格納する
        // ※APIデータの内容上、FloorPlanが格納されている＝中古マンション等になってしまう。
        foreach ($responses["data"] as $data) {

            if (array_key_exists("FloorPlan", $data) && array_key_exists("BuildingYear", $data)) {

                $selected_estates[] = $data;
            }
        }

        // $selected_estatesを返す（別のクラス、関数で使えるようにする）
        // これが使われている先がわからない。
        // 全体の流れ的に、EstateFilterクラスの気がする。
        return $selected_estates;
    }

    // 以下の関数はおそらく不要
    // function parseResult($responses){

    // }

    
}