@extends('layouts.app')

@section('content')

@include("common.content-header")


<h3><a href="{{ url('/api') }}">相場チェック</a></h3>
<a href="https://www.land.mlit.go.jp/webland/api.html" target="_blank">不動産取引価格情報取得API</a>
<br>
<a href="https://www.soumu.go.jp/denshijiti/code.html" target="_blank">市区町村コード一覧</a>
<br>
<a href="https://www.land.mlit.go.jp/webland/servlet/MainServlet" target="_blank">不動産取引価格検索</a>
<br>
<a href="https://zenn.dev/uedayou/articles/3b1459597bd017" target="_blank">行政区画ポリゴン付き住所オープンデータ提供サイトの紹介</a>
<br>
<a href="https://uedayou.net/loa/" target="_blank">Linked Open Addresses Japan</a>
<br>
<br>

<form id="search-form" method="post" action="{{ url("/api") }}">
    @csrf
        {{-- 取引時期を選ぶ --}}
        <div class="card mb-3">
            <div class="card-header">取引時期を選ぶ</div>
            <div class="card-body">    
                    <label>取引開始時期（年）</label>
                    <div>
                        <input type="number" name="from" value="{{ $thisYear }}">
                    </div>
        
                    <label>取引開始時期（月）</label>
                    <div>
                        <select name="from_month">
                            <option value="">選択してください</option>
                            <option value="1">1月～3月</option>
                            <option value="2">4月～6月</option>
                            <option value="3">7月～9月</option>
                            <option value="4">10月～12月</option>
                        </select>
                    </div>
        
                    <label>取引終了時期</label>
                    <div>
                        <input type="number" name="to" value="{{ $thisYear }}">
                    </div>
        
                    <label>取引終了時期（月）</label>
                    <div>
                        <select name="to_month">
                            <option value="">選択してください</option>
                            <option value="1">1月～3月</option>
                            <option value="2">4月～6月</option>
                            <option value="3">7月～9月</option>
                            <option value="4">10月～12月</option>
                        </select>
                    </div>
            </div>
        </div>

        {{-- 相場チェック_マイ物件から選ぶ --}}
        <div class="card mb-3">
            <div class="card-header">相場チェック_マイ物件から選択する時</div>
            
            <div class="card-body">
                <label>マイ物件DBから選ぶ</label>
                <div>
                    <select name="myEstateDB">
                        <option value="">選択してください</option>

                        @foreach($myEstates as $key => $myEstate)
                            <option value="{{$key}}" @if($myEstates == request()->get("myEstates")) selected @endif>{{$myEstate["EstateName"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- 相場チェック_検索フォームから入力する --}}
        <div class="card mb-3">
            <div class="card-header">相場チェック_検索フォームから入力する</div>
            <div class="card-body">

                {{-- <label>物件種目</label>
                <div>
                    <select name="Types">
                        <option value="">選択してください</option>

                        @foreach($Types as $key => $Type)
                            <option value="{{$Type}}" @if($Type == $estate->Type) selected @endif>{{$Type}}</option>
                        @endforeach
                    </select>
                </div> --}}

                <label>都道府県</label>
                <div>
                    <select id="select-prefecture" name="prefectures" onchange="on_change_prefecture();">
                        <option value="">選択してください</option>

                        @foreach($prefectures as $key => $prefecture)
                            <option value="{{$key}}">{{$prefecture}}</option>
                        @endforeach
                    </select>
                </div>

                <label>市区町村</label>
                <div>
                    <select id="select-city" name="citys" onchange="on_change_city();">
                        <option value="">選択してください</option>

                        {{-- ApiController.phpでは、$estates = Estate::find(1);となっているので、id=1だけのデータベースのような気がする --}}
                        {{-- @foreach($citys as $key => $city)
                            <option value="{{$key}}">{{$city}}</option>
                        @endforeach --}}
                    </select>
                </div>

                <label>地区名</label>
                <div class="form-group mb-3">
                    <select id="select-DistrictName" name="DistrictName">
                        <option value="">選択してください</option>
                    </select>

                    {{-- 以下、市区町村を選択する事で動的に地区名の表示を変更する改造をするためにコメントアウト --}}
                    {{-- <input type="text" name="DistrictName" class="form-control" value="{{ old('DistrictName', @$input["DistrictName"]) }}"> --}}
                </div>

                <label>間取り</label>
                <div>
                    <select name="FloorPlans">
                        <option value="">選択してください</option>
                        
                        {{-- ApiController.phpでは、$estates = Estate::find(1);となっているので、id=1だけのデータベースのような気がする --}}
                        @foreach($FloorPlans as $key => $FloorPlan)
                            <option value="{{$FloorPlan}}">{{$FloorPlan}}</option>
                        @endforeach
                    </select>
                </div>

                <label>築年</label>
                <div class="form-group mb-3">
                    <input type="text" name="BuildingYear" class="form-control" value="{{ old('BuildingYear', @$input["BuildingYear"]) }}">
                </div>
            </div>
        </div>

    <input class="btn btn-primary" type="button" value="送信(Ajax)" onclick="search_api();" />

    <input class="btn btn-primary" type="submit" value="送信" />
</form>

<script>
    function search_api(){
        // 上記formタグ（id="search-form"）で入力した値をシリアル関数を使って変換する。
        const form_data = $("#search-form").serialize();

        // フォームの内容をAjaxを使って、"/api"に送信する
        $.post("{{ url('/api') }}", form_data, function(data, status){

            // 以下のdivタグ（id=result）の中に相場チェックの結果を表示する
            $("#result").html(data);
        });
    
    }


    // 入力したarea（都道府県）に応じて、citys（市区町村）フォームの選択肢を動的に変更する。
    function on_change_prefecture(){
        const area = $("#select-prefecture").val();

        $.getJSON("{{ url('/api/city') }}?area=" + area, function(data, status){
            $("#select-city").html('<option value="">選択してください</option>');

            for(item of data["data"]){
                const $option = $("<option></option>").attr("value",item.id).text(item.name);
                $("#select-city").append($option);
            }
        });
    }

    // 入力したcitys（市区町村）に応じて、DistrictName（地区名）フォームの選択肢を動的に変更する。
    function on_change_city(){

        const city =$("#select-city").val();
        // alert(city);//->表示問題なし

        $.getJSON("{{ url('api/DistrictName') }}?citys=" + city, function(data, status){

            // 市区町村が選択されていない時の表示
            $("#select-DistrictName").html('<option value="">選択してください</option>');

            for(item of data["data"]){
                const $option = $("<option></option>").attr("value",item.id).text(item.name);
                $("#select-DistrictName").append($option);
            }
        });
    }
</script>


{{-- 別途Viewを用意するのがめんどうだったので、とりあえず、firebase.blade.phpは"/api"で表示する --}}
{{-- @include("common.firebase") --}}

<div id="result"></div>


@endsection