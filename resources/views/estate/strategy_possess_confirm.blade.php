@extends('layouts.app')

@section('content')

@include("common.content-header")

<h3>strategy_possess_confirm</h3>
<form method="post" action="{{ route('strategy_possess_send') }}">
	@csrf
	<div class="card mb-3">
        <div class="card-header">保有</div>
        
        <div class="card-body row">
            <div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">収入</div>
                {{--  --}}
                <div>
					家賃収入（円/月）：{{ $input_possess['property_income'] }}
				</div>

				<div>
					還付金（円/年）：{{ $input_possess['refund'] }}
				</div>
			</div>

			<div class="col-6">
                <div class="card text-white bg-primary mb-3 text-center">支出</div>

				<div class="card mb-3">
					<div class="card-header mb-3">物件保有</div>
					<div>
						物件管理による経費（円/年）：{{ $input_possess['property_possess_expense'] }}
					</div>
				</div>
				
				{{-- 固定資産税、都市計画税はマイ物件から取得、所得税、住民税はControllerで計算しているため入力フォームを設定しない --}}

				<div class="card mb-3">
					<div class="card-header mb-3">保険</div>
					<div>
						年間火災保険料（円/年）：{{ $input_possess['fire_insurance'] }}
					</div>

					<div>
						年間地震保険料（円/年）：{{ $input_possess['erthquake_insurance'] }}
					</div>

					<div>
						年間その他保険料（円/年）：{{ $input_possess['other_insurance'] }}
					</div>
				</div>

				<div class="card mb-3">
					<div class="card-header mb-3">その他</div>
					<div>
						個別修繕費（円/回）：{{ $input_possess['repair_cost'] }}
					</div>

					<div>
						個別修繕開始年：{{ $input_possess['repair_start'] }}
					</div>

					<div>
						個別修繕の頻度：{{ $input_possess['repair_frequency'] }}
					</div>

					<div>
						立ち退き費用（円/回）※頻度は以下で指定：{{ $input_possess['eviction_cost'] }}
					</div>

					<div>
						立ち退き開始年：{{ $input_possess['eviction_start'] }}
					</div>

					<div>
						立ち退きの頻度：{{ $input_possess['eviction_frequency'] }}
					</div>

					<div>
						その他（円/回）：{{ $input_possess['other_cost'] }}
					</div>

					<div>
						その他開始年：{{ $input_possess['other_start'] }}
					</div>

					<div>
						その他の頻度：{{ $input_possess['other_frequency'] }}
					</div>
				</div>
			</div>
		</div>
	</div>



	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>

@endsection