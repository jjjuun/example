@extends('layouts.app')

@section('content')

@include("common.content-header")

<div class="card">
    <div class="card-header">マイ物件</div>

    <div class="card-body">
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>物件名</th>
                    <th>更新日</th>
                    <th>ステータス</th>
                </tr>
            </thead>

            <tbody>
                @foreach($estate_list as $estate)
                    <tr>
                        <td><a href="/estate/edit/{{ $estate["id"] }}">{{$estate->EstateName}}</a></td>
                        <td>{{\Carbon\Carbon::parse($estate->updated_at)->format("Y年m月d日")}}</td>
                        <td>{{$estate->status}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $estate_list->links() }}


    </div><!-- //.card-body -->


</div><!-- //.card -->

@endsection