@extends('layouts.app')

@section('content')

@include("common.content-header")

<div class="card">
    <div class="card-header">マイ物件の登録</div>

    <div class="card-body">
        <form method="POST" action="{{ route('estate.create.check') }}">
            @csrf

            {{-- 物件名 --}}
            <label>物件名</label>
            <div class="form-group mb-3">
                <input type="text" name="EstateName" class="form-control">{{ old('estateName', @$input["estateName"]) }}
            </div>

            {{-- 物件種目（例：中古マンション等） --}}
            <label>物件種目</label>
            <div class="form-group mb-3">
                <select name="Type">
                    <option value="">選択してください</option>
                    
                    @foreach($Types as $key => $Type)
                        <option value="{{$Type}}" @if($Types == request()->get('Type')) selected @endif>{{$Type}}</option>
                    @endforeach
                </select>
            </div>

            {{-- 都道府県名 --}}
            <label>都道府県</label>
            <div class="form-group mb-3">
                <select name="Prefecture">
                    <option value="">選択してください</option>

                    @foreach($prefectures as $key => $prefecture)
                        <option value="{{$prefecture}}" @if($prefectures == request()->get('prefecture')) selected @endif>{{$prefecture}}</option>
                    @endforeach
                </select>
            </div>

            {{-- 市区町村 --}}
            <label>市区町村</label>
            <div class="form-group mb-3">
                <select name="Municipality">
                    <option value="">選択してください</option>
    
                    @foreach($Municipalitys as $key => $Municipality)
                        <option value="{{$Municipality}}" @if($Municipalitys == request()->get('Municipality')) selected @endif>{{$Municipality}}</option>
                    @endforeach
                </select>
            </div>

            {{-- 地区名 --}}
            {{-- 市区町村のように一覧になっていないため、２３区の町名を調べて自分で作成する必要がある？ --}}
            {{-- 一旦、checkboxではなく、textフォームで進める --}}
            <div>
                <label>地区名</label>
                <div class="form-group mb-3">
                    <input type="text" name="DistrictName" class="form-control">{{ old('DistrictName', @$input["DistrictName"]) }}
                </div>
            </div>

            {{-- 間取り --}}
            <label>間取り</label>
            <div class="form-group mb-3">
                <select name="FloorPlan">
                    <option value="">選択してください</option>
                    
                    @foreach($FloorPlans as $key => $FloorPlan)
                        <option value="{{$FloorPlan}}" @if($FloorPlans == request()->get('FloorPlan')) selected @endif>{{$FloorPlan}}</option>
                    @endforeach
                </select>
            </div>

            {{-- 築年 --}}
            {{-- APIは元号になっている、西暦にすべきか？ --}}
            <label>築年</label>
            <div class="form-group mb-3">
                <input type="text" name="BuildingYear" class="form-control" placeholder="平成14年">{{ old('BuildingYear', @$input["BuildingYear"]) }}
            </div>

            {{-- 購入価格 --}}
            <label>購入価格（万円）</label>
            <div class="form-group mb-3">
                <input type="number" name="BuyPrice" class="form-control" placeholder="">{{ old('BuyPrice', @$input["BuyPrice"]) }}
            </div>

            {{-- 家賃収入 --}}
            <label>家賃収入（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_income" class="form-control" placeholder="">{{ old('property_income', @$input["property_income"]) }}
            </div>

            {{-- 管理費 --}}
            <label>管理費（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="property_management_cost" class="form-control" placeholder="">{{ old('property_management_cost', @$input["property_management_cost"]) }}
            </div>

            {{-- 修繕積立費 --}}
            <label>修繕積立費（円/月）</label>
            <div class="form-group mb-3">
                <input type="number" name="perperty_maintenance_cost" class="form-control" placeholder="">{{ old('perperty_maintenance_cost', @$input["perperty_maintenance_cost"]) }}
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">保存</button>
        </form>
    </div><!-- //.card-body -->

</div><!-- //.card -->

@endsection