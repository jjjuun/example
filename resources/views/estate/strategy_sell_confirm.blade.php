@extends('layouts.app')

@section('content')

@include("common.content-header")

<h3>strategy_sell_confirm</h3>
<form method="post" action="{{ route('strategy_sell_send') }}">
	@csrf


    <div class="card mb-3">
        <div class="card-header">購入</div>
        
        <div class="card-body row">
            <div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">収入</div>
                {{--  --}}
                <div>
                    （仮）物件売却費用（万円）：{{ $input_sell['KARI_SELL_PRICE'] }}
                </div>
            </div>

            <div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">支出</div>

                <div>
                    仲介手数料率（％）：{{ $input_sell['sell_fee_rate'] }}
                </div>

				<div>
                    収入印紙代（円）：{{ $input_sell['sell_stamp_fee'] }}
                </div>

				<div>
                    その他（円）：{{ $input_sell['sell_other_fee'] }}
                </div>
            </div>
        </div>
    </div>

	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>
@endsection