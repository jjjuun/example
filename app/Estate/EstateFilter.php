<?php

namespace App\Estate;

use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstateFilter {
    /**
     * このクラスでは、以下の機能を実装する。
     * ※EstateRepositoryで記述してもいいが、リファクタリングの観点では別のクラスを設けるのがベター。
     * add：フォームの値から検索条件を作る関数。
     * addMyEstate：マイ物件に合致した検索条件を作る関数。
     * filter：条件に一致する時に、$new_estatesに格納するための変数。最後に、$new_estatesを返す。
     */

    //  プロパティに$filtersを指定する。初期値は空配列。
    // 後で、使用する際には、privateにしているので、$this->を使って各関数内でプロパティに格納する変数を定義する。
    private $filters = [];

    function add($key, $value){
        // フォームの値から検索条件を作る関数。

        // dd("add",$key,$value);//->$key="FloorPlan",$value="２ＬＤＫ"

        $this->filters[$key] = $value;
        // dd($this);//->以下の通り。
        /**
         * 入力フォームの時（20221～20222、東京都、2LDK、平成15年）
         * -filters: array:1 [▼
         * "FloorPlan" => "２ＬＤＫ"
         * ]
         */

        /**
         * dd($key,$value,$this); //-> $key:BuildingYear, $value:平成15年,$this:array["BuidlingYear" => "平成15年"]
         * ApiControllerのsearch関数のif(isset($input[フォームの値]))を通過するたびに、add関数が呼び出される。
         * そのため、複数条件（例：BuildingYearとFloorPlan）が入力された場合でも、ここでのddでは、フォームの値のひとつずつしか確認できない。
         */
    }

    function addMyEstate($key, $value){

        // dd("addMyEstate",$key,$value);//->以下の通り。送信方法：foreach下で、$filter->addMyEstate($key, $value);
        /**$key は以下。
         * "Type"
         * "Prefecture"
         * "Municipality"
         * "DistrictName"
         * "FloorPlan"
         * "BuildingYear"
         */

        /**$valueは以下。
         * "中古マンション等"
         * "東京都"
         * "港区"
         * "六本木"
         * "２ＬＤＫ"
         * "平成15年"
         */
        // 

        $this->filters[$key] = $value;
        // dd("addMyEstate",$this);//->以下の通り。
        /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
         * -filters: array:1 [▼
         * "Type" => "中古マンション等"
         * ]
         */
        // 
    }


    function filter($estates){
        // $estatesを引数にして、条件に一致する時に、$new_estatesに格納するための変数

        // dd("estates at filter", $estates);//->以下の通り。
        /**マイ物件の時
        * array:1959 [▼
         * 0 => array:17 [▶]
         * 1 => array:17 [▶]
         * 2 => array:16 [▶]
         * 3 => array:17 [▶]
         * ....
         * 1957 => array:16 [ …16]
         * 1958 => array:15 [ …15]
         * ]
         */

        /**入力フォームの時（20221-20222、東京都）
         * array:1959 [▼
         * 0 => array:17 [▶]
         * 1 => array:17 [▶]
         * 2 => array:16 [▶]
         * 3 => array:17 [▶]
         * ....
         * 1957 => array:16 [ …16]
         * 1958 => array:15 [ …15]
         * ]
         */
        // 

        $new_estates = [];

        foreach($estates as $estate){
            $ret = true;
            
            foreach($this->filters as $key => $value){
                // dd("foreach(this->filters as key => value)",$this,$key,$value);//->以下の通り。
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

                if($estate[$key] != $value){
                    $ret = false;
                    break;
                }
            }

            if($ret){

                $new_estates[] = $estate;
                // dd("new_estates",$new_estates);//->以下の通り。
                /**入力フォームの時（20221～20222、東京都、2LDK、平成15年）
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
                // 
            }
        }

        return $new_estates;
    }

}