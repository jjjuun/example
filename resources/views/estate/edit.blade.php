@extends('layouts.app')

@section('content')

<div class="card mb-5">
    <div class="card-header">マイ物件編集
        {{-- 削除 --}}
        <form method="POST" action="/estate/delete/{{ $edit_estate["id"] }}" id="delete-form">
            @csrf
            <input type="hidden" name="EstateName" value="{{ $edit_estate["EstateName"] }}">
            <button><i id="delete-button" class="fas fa-trash"></i></button>
        </form>
    </div>

    <div class="card-body">
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Create</th>
                    <th>Update</th>
                    <th>物件名</th>
                    <th>物件種目</th>
                    <th>都道府県</th>
                    <th>市区町村</th>
                    <th>地区名</th>
                    <th>間取り</th>
                    <th>築年</th>
                    <th>購入価格（万円）</th>
                    <th>家賃収入（円/月）</th>
                    <th>管理費（円/月）</th>
                    <th>修繕積立費（円/月）</th>
                    <th>固定資産税（円/年）</th>
                    <th>都市計画税（円/年）</th>
                    <th>ステータス</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{$edit_estate["id"]}}</td>
                    <td>{{\Carbon\Carbon::parse($edit_estate->created_at)->format("Y年m月d日")}}</td>
                    <td>{{\Carbon\Carbon::parse($edit_estate->updated_at)->format("Y年m月d日")}}</td>
                    <td>{{$edit_estate["EstateName"]}}</td>
                    <td>{{$edit_estate["Type"]}}</td>
                    <td>{{$edit_estate["Prefecture"]}}</td>
                    <td>{{$edit_estate["Municipality"]}}</td>
                    <td>{{$edit_estate["DistrictName"]}}</td>
                    <td>{{$edit_estate["FloorPlan"]}}</td>
                    <td>{{$edit_estate["BuildingYear"]}}</td>
                    <td>{{$edit_estate["BuyPrice"]}}</td>
                    <td>{{$edit_estate["property_income"]}}</td> {{-- 家賃収入 --}}
                    <td>{{$edit_estate["property_management_cost"]}}</td> {{-- 管理費 --}}
                    <td>{{$edit_estate["perperty_maintenance_cost"]}}</td> {{-- 修繕積立費 --}}
                    <td>{{$edit_estate["KARI_PROPERTY_TAX"]}}</td> {{-- 固定資産税 --}}
                    <td>{{$edit_estate["KARI_CITY_PLAN_TAX"]}}</td> {{-- 都市計画税 --}}
                    <td>{{$edit_estate["status"]}}</td>
                    <td>{{$edit_estate["detail"]}}</td>
                </tr>
            </tbody>
        </table>

    </div><!-- //.card-body -->


</div><!-- //.card -->

{{-- statusとdetailだけ変更できるようにする --}}
<form method="POST" action="{{ route("estate.update", ["id" => $edit_estate["id"]]) }}">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user["id"] }}">
    <div class="card mb-3">
        <div class="card-header">ステータス</div>

        <div class="card-body">
            <textarea name="status" class="form-control" rows="1">{{ $edit_estate["status"] }}</textarea>
        </div>
    </div>
    

    <div class="card mb-3">
        <div class="card-header">詳細</div>

        <div class="card-body">
            <textarea name="detail" class="form-control" rows="5">{{ $edit_estate["detail"] }}</textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">更新</button>
</form>

@endsection