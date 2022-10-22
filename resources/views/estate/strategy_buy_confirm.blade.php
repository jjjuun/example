@extends('layouts.app')

@section('content')

@include("common.content-header")

<h3>strategy_buy_confirm</h3>
<form method="post" action="{{ route('strategy_buy_send') }}">
	@csrf

    <div class="card mb-3">
        <div class="card-header">購入</div>
        
        <div class="card-body row">
            <div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">ローン</div>
                {{--  --}}
                <div>
                    不動産購入価格（万円）：{{ $input_buy['buy_price'] }}
                </div>

                <div>
                    自己資金（万円）：{{ $input_buy['loan_own_resource'] }}
                </div>

                <div>
                    返済方式：{{ $input_buy['loan_repayment_method'] }}
                </div>

                <div>
                    返済期間（年）：{{ $input_buy['loan_repayment_duration'] }}
                </div>

                <div>
                    返済開始（年）：{{ $input_buy['loan_repayment_start_year'] }}
                </div>

                <div>
                    返済開始（月）：{{ $input_buy['loan_repayment_start_month'] }}
                </div>

                <div>
                    返済開始時の年齢（歳）：{{ $input_buy['loan_repayment_start_age'] }}
                </div>

                <div>
                    金利方式：{{ $input_buy['loan_interest_rate_method'] }}
                </div>

                <div>
                    ローン返済年利（％）：{{ $input_buy['loan_interest_rate'] }}
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">支出</div>

                <div>
                    マイ物件：{{ $input_buy['my_estate'] }}
                </div>

                <div>
                    仲介手数料率（％）：{{ $input_buy['buy_brokerage_rate'] }}
                </div>

                <div>
                    収入印紙代（円）：{{ $input_buy['buy_stamp_fee'] }}
                </div>

                <div>
                    登記費用（円）：{{ $input_buy['buy_registration_fee'] }}
                </div>

                <div>
                    その他（円）：{{ $input_buy['buy_other_fee'] }}
                </div>
            </div>
        </div>
    </div>

	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>

<div>
    <h3>ローン返済確認</h3>
    <h3>返済方式：{{ $input_buy['loan_repayment_method'] }}</h3>
    <h3>ローン返済回数：{{ $loan_repayment }}</h3>
    <h3>ローン返済総額：{{ $calc_arrays_loan[$input_buy['loan_repayment_duration']-1][11]["total_repayment"]; }}</h3>
    <h3>利息総額：{{ $calc_arrays_loan[$input_buy['loan_repayment_duration']-1][11]["total_interest_payment"]; }}</h3>

    <table class="table table-bordered">
        <thead>
            <tr style="overflow:auto">
                <th style="position: sticky; top: 0; left: 0; background: #fff">ローン支払額</th>
                <th style="position: sticky; top: 0; left: 0; background: #fff">支払い利息</th>
                <th style="position: sticky; top: 0; left: 0; background: #fff">支払い元金</th>
                <th style="position: sticky; top: 0; left: 0; background: #fff">ローン残債</th>
            </tr>
        </thead>

        <tbody>
            @foreach($calc_arrays_loan as $calc_array_loan)
                @for($j = 0; $j < 12; $j++)
                    <tr>
                        <td>{{ intval($calc_array_loan[$j]["loan_repayment_month"]); }}</td>
                        <td>{{ intval($calc_array_loan[$j]["interest_payment"]); }}</td>
                        <td>{{ intval($calc_array_loan[$j]["principal_repayment"]); }}</td>
                        <td>{{ intval($calc_array_loan[$j]["loan_remain"]); }}</td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
</div>


@endsection