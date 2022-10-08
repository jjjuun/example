<form method="post" action="{{ route('estate.create.store') }}">
	@csrf

	<div class="card">
		<div class="card-header">登録内容確認</div>

		<div class="card-body">
			<div class="mb-3">
				物件名：{{ $input["EstateName"] }}
			</div>	

			<div>
				物件種目：{{ $input["Type"] }}
			</div>

			<div>
				都道府県：{{ $input["Prefecture"] }}
			</div>

			<div>
				市区町村：{{ $input["Municipality"] }}
			</div>

			<div>
				地区名：{{ $input["DistrictName"] }}
			</div>

			<div>
				間取り：{{ $input["FloorPlan"] }}
			</div>

			<div>
				築年：{{ $input["BuildingYear"] }}
			</div>

			<div>
				購入価格：{{ $input["BuyPrice"] }}万円
			</div>

			<div>
				家賃収入：{{ $input["BuyPrice"] }}円/月
			</div>

			<div>
				管理費：{{ $input["BuyPrice"] }}円/月
			</div>

			<div>
				修繕積立費：{{ $input["BuyPrice"] }}円/月
			</div>
		</div>

	</div>


	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>