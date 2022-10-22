@extends('layouts.app')

@section('content')

@include("common.content-header")
    strategy_output

    <div class="card-header">出口戦略チェック</div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
                <tr style="overflow:auto">
                    <th style="position: sticky; top: 0; left: 0; background: #fff">年</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">月</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">年齢</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">家賃収入</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">還付金</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">管理費</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">修繕積立費</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">月間ローン返済額</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">ローン残債</th>
                    {{-- <th style="position: sticky; top: 0; left: 0; background: #fff">（仮）固定資産税</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">（仮）都市計画税</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">（仮）所得税</th>
                    <th style="position: sticky; top: 0; left: 0; background: #fff">（仮）住民税</th> --}}
                </tr>
            </thead>

            <tbody>
                @foreach($calc_arrays as $calc_array)
                    @for($j = 0; $j < 12; $j++)
                        <tr>
                            <td>{{ $calc_array[$j]["year"]; }}</td>
                            <td>{{ $calc_array[$j]["month"]; }}</td>
                            <td>{{ $calc_array[$j]["loan_repayment_start_age"]; }}</td>
                            <td>{{ $calc_array[$j]["property_income"]; }}</td>
                            <td>{{ $calc_array[$j]["refund"]; }}</td>
                            <td>{{ $calc_array[$j]["property_management_cost"]; }}</td>
                            <td>{{ $calc_array[$j]["property_maintenance_cost"]; }}</td>
                            <td>{{ intval($calc_array[$j]["loan_repayment_month"]); }}</td>
                            <td>{{ intval($calc_array[$j]["loan_remain"]); }}</td>
                            {{-- <td>{{ $calc_array[$j]["KARI_PROPERTY_TAX"]; }}</td>
                            <td>{{ $calc_array[$j]["KARI_CITY_PLAN_TAX"]; }}</td>
                            <td>{{ $calc_array[$j]["KARI_INCOME_TAX"]; }}</td>
                            <td>{{ $calc_array[$j]["KARI_RESIDENT_TAX"]; }}</td> --}}
                        </tr>
                    @endfor
                @endforeach
            </tbody>
        </table>

    </div>
    
    
@endsection