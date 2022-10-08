<?php

namespace App\Http\Controllers;

// use句
use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Validator;

class SampleFormController extends Controller
{
    // 複数関数をまたいで使用する変数は関数の外で指定する
    // private $formItems = ["name", "title", "body"];


    // show関数を指定する
    function show(Request $request){
        $input = $request->session()->get("form_input");

        return view('form',[
            "input" => $input
        ]);
    }

    // post関数を指定する
    function post(Request $request){

        // 【20220906】▼▼▼登録フォームに画像を追加▼▼▼
        // $inputはimage以外のキーを取得する
        // とりあえず、sessionの対象外とするため、ここでは$upload_image変数を指定しない
        $input = $request->except("image");

        // 以下、オリジナル
        // $input = $request->all();
        // 【20220906】
        

        
        // バリデーション設定
        $request->validate([
            "name" => "required",
            "name_kana" => "required",
            "email" => "required|email|unique:forms",

            // 【20220906】▼▼▼登録フォームに画像を追加▼▼▼
            "agree" => "required",
            'image' => 'file|image|mimes:png,jpeg'
            // 【20220906】▲▲▲登録フォームに画像を追加▲▲▲
        ]);

        //セッションに書き込む（key名：form_input）
        $request->session()->put("form_input", $input);

        $image = $request->file("image");
        // dd($image);

        if($image){
            // アップロードされた画像を保存する
            $path = $image->store("uploads","public");
    
            // 画像の保存に成功したらDBに記録する
            if($path){
                $request->session()->put("form_input_image",[ 
                    "file_name" => $image->getClientOriginalName(),
                    "file_path" => $path
                ]);
            }
        }


        // 入力後のリダイレクト先を設定
        return redirect("/form/confirm");

    }

    // confirm関数を指定する
    function confirm(Request $request){
        // セッションから値を取り出す
        $input = $request->session()->get("form_input");

        // セッションに値がない時はフォームに戻る
        if(!$input){
            return redirect("/form");
        }

        $image = $request->session()->get("form_input_image");
        

        // 引数inputに変数inputを渡してform_confirm.blade.phpを返す
        return view("form_confirm",["input" => $input, "image" => $image]);
    }

    // send関数を指定する
    function send(Request $request){
        // セッションを取り出す
        $input = $request->session()->get("form_input");

        // セッションに値が無い時はフォームに戻る
        if(!$input){
            return redirect("/form");
        }

        // データベースに格納する変数を指定する
        // key名と同じ名前で変数を指定し、input配列の各keyから値を取り出す。
        $name = $input["name"];
        $name_kana = $input["name_kana"];
        $title = $input["title"];
        $body = $input["body"];
        $contact_type = $input["contact_type"];
        $email = $input["email"];
        $age = $input["age"];
        $pref = $input["pref"];
        $agree = $input["agree"];
        

        $form = new Form();
        $form->name = $name;
        $form->name_kana = $name_kana;
        $form->title = $title;
        $form->body = $body;
        $form->contact_type = $contact_type;
        $form->email = $email;
        $form->age = $age;
        $form->pref = $pref;
        $form->agree = $agree;
        
        // 【20220906】▼▼▼登録フォームに画像を追加▼▼▼
        // $imageはDBに格納するデータとして追加したため別の変数を設定する。
        // $image = $request->file("image");

        // if($image){
        // //     // アップロードされた画像を保存する
        //     $path = $image->store("uploads","public");

        // //     // 画像の保存に成功したらDBに記録する
        //     if($path){
        //         Form::create([
        //             "file_name" => $image->getClientOriginalName(),
        //             "file_path" => $path
        //         ]);
        //     }
        // }

        $image = $request->session()->get("form_input_image");
        if($image){
            $form->file_name = $image["file_name"];
            $form->file_path = $image["file_path"];
        }
        
        // dd($image);

        // 【20220906】▲▲▲登録フォームに画像を追加▲▲▲

        // DBに保存する
        if($request->has("submit")){
            $form->save();
        }

        // 「戻る」をクリックした時の処理
        if($request->has("back")){
            //戻るボタンが押された時の処理
            return redirect("/form")->withInput($input);
        }

        // セッションを空にする
        $request->session()->forget("form_input");

        return redirect("/form/complete");
    }

    // complete関数を指定する
    function complete(){	
		return view("form_complete");
	}


    // index関数を指定する
    function index(Request $request){

        $query = Form::orderBy('created_at');

        // キーワードを受け取る
        $keywords = $request->input("keywords");
        
        // id、名前、なまえで検索
        $check_kind_one = $request->input("check_kind_one");
        
        // 県で検索
        $pref_result = $request->input("pref_result");

        if($check_kind_one && $keywords){
            if($check_kind_one == "id"){
                $query->where($check_kind_one,"=",$keywords);
            }else{
                $query->where($check_kind_one,"like","%" . $keywords . "%");
            }
        }
        
        if($pref_result){
            $query->where("pref","=",$pref_result );
        }


        /*
        // 上記で取得したキーワードを使ってForm::whereを作成する
        if ($request->filled("keywords") && $request->filled("pref_result")){
            // 両方とも検索欄が埋まっている場合
            $results = Form::where($check_kind_one,"=",$keywords)->where("pref","=",$pref_result)->get();
            // $results = "両方とも検索欄が埋まっている場合";

        } elseif ($request->filled("keywords") && $pref_result==""){
            // id等の検索欄しか埋まっていない場合
            $results = Form::where($check_kind_one,"=",$keywords)->get();
            // $results = "id等の検索欄しか埋まっていない場合";

        } elseif (!$request->keywords && !$request->pref_result) {
            // 両方入力されていない場合
            $results = [];

        } elseif ($keywords=="" && filled("pref_result")){
            // 県の検索欄しか埋まっていない場合
            $results = Form::where("pref","=",$pref_result)->get();
            // $results = "県の検索欄しか埋まっていない場合";
        } 
        */
        

        // メモ一覧を取得する
        $contacts = $query->paginate(5);

        return view("index", [
            "contacts" => $contacts,
            "results" => $contacts,
            "keywords" => $keywords,
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
            ]
        ]);
        


        // all()はDB格納されているデータを全件指定して取り出す
        // $contact = Form::all();
        // dd($contact);

        // find()はid指定
        // $contact= Form::find(3);
        // dd($contact);

        // 新着何件取り出すようなやり方
        // $paginate = Form::paginate(2);
        // echo $paginate->links();
        // dd($paginate);

        // where()で条件を指定して取り出す
        // ageが20より大きいidのすべて
        // $ret = Form::where("age",">",20)->get();

        // ageが10以上でid基準で降順でページネーションで出力する
        // $ret = Form::where("age",">=",5)->orderBy("id","desc")->paginate(2);
        // dd($ret);
    }
}
