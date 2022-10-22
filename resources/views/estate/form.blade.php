@extends('layouts.app')

@section('content')

@include("common.content-header")

<div class="card">
    <div class="card-header">マイ物件の登録</div>

    <div class="card-body">
        <form method="POST" action="{{ route('estate.create.check') }}">
            @csrf

            <label>物件名</label>
            <div class="form-group mb-3">
                <input type="text" name="EstateName" class="form-control">{{ old('estateName', @$input["estateName"]) }}
            </div>

            <label>物件種目</label>
            <div class="form-group mb-3">
                <select name="Type">
                    <option value="">選択してください</option>
                    
                    @foreach($Types as $key => $Type)
                        <option value="{{$Type}}" @if($Types == request()->get('Type')) selected @endif>{{$Type}}</option>
                    @endforeach
                </select>
            </div>

            <label>都道府県</label>
            <div class="form-group mb-3">
                <select name="Prefecture">
                    <option value="">選択してください</option>

                    @foreach($prefectures as $key => $prefecture)
                        <option value="{{$prefecture}}" @if($prefectures == request()->get('prefecture')) selected @endif>{{$prefecture}}</option>
                    @endforeach
                </select>
            </div>

            <label>市区町村</label>
            <div class="form-group mb-3">
                <select name="Municipality">
                    <option value="">選択してください</option>
    
                    @foreach($Municipalitys as $key => $Municipality)
                        <option value="{{$Municipality}}" @if($Municipalitys == request()->get('Municipality')) selected @endif>{{$Municipality}}</option>
                    @endforeach
                </select>
            </div>

            {{-- 市区町村のように一覧になっていないため、２３区の町名を調べて自分で作成する必要がある？ --}}
            {{-- textフォームでリリースする --}}
            <div>
                <label>地区名</label>
                <div class="form-group mb-3">
                    <input type="text" name="DistrictName" class="form-control"{{ old('DistrictName', @$input["DistrictName"]) }}>
                </div>
            </div>

            <label>間取り</label>
            <div class="form-group mb-3">
                <select name="FloorPlan">
                    <option value="">選択してください</option>
                    
                    @foreach($FloorPlans as $key => $FloorPlan)
                        <option value="{{$FloorPlan}}" @if($FloorPlans == request()->get('FloorPlan')) selected @endif>{{$FloorPlan}}</option>
                    @endforeach
                </select>
            </div>

            <label>建物の構造</label>
            <div class="form-group mb-3">
                <select name="Structure">
                    <option value="">選択してください</option>
                    <option value="木造">木造</option>
                    <option value="鉄骨造_one">鉄骨（鉄骨の厚み:3～4mm）</option>
                    <option value="鉄骨造_two">鉄骨（鉄骨の厚み:4mm以上）</option>
                    <option value="ＲＣ">ＲＣ</option>
                    <option value="ＳＲＣ">ＳＲＣ</option>
                </select>
            </div>

            <label>築年</label>
            <div class="form-group mb-3">
                <input type="number" name="BuildingYear" class="form-control" placeholder="2022" {{ old('BuildingYear', @$input["BuildingYear"]) }}>
            </div>

            <label>取得年</label>
            <div class="form-group mb-3">
                <input type="number" name="GetYear" class="form-control" placeholder="2022" {{ old('GetYear', @$input["GetYear"]) }}>
            </div>

            <label>購入価格（万円）</label>
            <div class="form-group mb-3">
                <input type="number" name="BuyPrice" class="form-control" placeholder="" {{ old('BuyPrice', @$input["BuyPrice"]) }}>
            </div>

            <label>家賃収入（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_income" class="form-control" placeholder="" {{ old('property_income', @$input["property_income"]) }}>
            </div>

            <label>管理費（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_management_cost" class="form-control" placeholder="" {{ old('property_management_cost', @$input["property_management_cost"]) }}>
            </div>

            <label>修繕積立費（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_maintenance_cost" class="form-control" placeholder="" {{ old('property_maintenance_cost', @$input["property_maintenance_cost"]) }}>
            </div>

            <label>固定資産税（円/年）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_tax" class="form-control" placeholder="" {{ old('property_tax', @$input["property_tax"]) }}>
                <p>固定資産税は物件購入前に物件営業の人に確認してみましょう。</p>
            </div>

            <label>都市計画税（円/年）</label>
            <div class="form-group mb-3">
                <input type="number" name="city_plan_tax" class="form-control" placeholder="" {{ old('city_plan_tax', @$input["city_plan_tax"]) }}>
                <p>固定資産税は物件購入前に物件営業の人に確認してみましょう。</p>
            </div>

            <label>固定資産標準額（土地）（円）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_std_land_price" class="form-control" placeholder="" {{ old('property_std_land_price', @$input["property_std_land_price"]) }}>
                <p>固定資産税は物件購入前に物件営業の人に確認してみましょう。</p>
            </div>

            <label>固定資産標準額（家屋）（円）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_std_house_price" class="form-control" placeholder="" {{ old('property_std_house_price', @$input["property_std_house_price"]) }}>
                <p>固定資産税は物件購入前に物件営業の人に確認してみましょう。</p>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">保存</button>
        </form>
    </div><!-- //.card-body -->

</div><!-- //.card -->

@endsection