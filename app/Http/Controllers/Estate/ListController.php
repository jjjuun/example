<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    function index(){

        $estate_list = Estate::where("user_id","=", Auth::id())
            ->where("DB_status", "=", 1)
            ->orderBy("id","asc")
            ->paginate(5);

        // var_dump($estate_list);

        return view("estate.index",[
            "estate_list" => $estate_list
        ]);
    }

    function edit($id){

        // ログインユーザーの情報を取得する
        $user = Auth::user();

        // 該当するidのマイ物件をestatesテーブルから取得する
        $edit_estate = Estate::where("id", $id)->where("user_id", $user["id"])->first();

        // dd($edit_estate);

        // 取得した$edit_estateをeedit.blade.phoファイルに渡す
        return view("estate.edit",[
            "edit_estate" => $edit_estate,
            "user" => $user
        ]);
    }

    function update(Request $request, $id){
        
        // フォームに入力した更新内容を取得する
        $updata_input = $request->all();

        // それぞれの更新内容を$update連想配列に格納し、Estate::updateを使えるようにする
        $update = [
            "status" => $updata_input["status"],
            "detail" => $updata_input["detail"]
        ];

        // estatesテーブルのidが一致した物に対して、updateをする。
        Estate::where("id", $id)->update($update);

        // "/estate/index"にリダイレクト
        return redirect("estate/index");
    }

    function delete(Request $request, $id){

        // 削除対象のデータを取得する
        $delete_input = $request->all();

        // DB_status=2を削除という論理削除を実行する
        Estate::where("id", $id)->update(["DB_status" => 2]);

        // "/estate/index"にリダイレクト
        return redirect("estate/index")->with("success", $delete_input["EstateName"]."を削除しました");
    }
}
