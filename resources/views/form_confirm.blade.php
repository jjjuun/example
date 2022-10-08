<h3>確認</h3>
<form method="post" action="{{ route('form.send') }}">
	@csrf

	{{--  --}}
	<label>名前</label>
	<div>
		{{ $input["name"] }}
	</div>

	{{--  --}}
	<label>なまえ</label>
	<div>
		{{ $input["name_kana"] }}
	</div>

	{{--  --}}
	<label>件名</label>
	<div>
		{{ $input['title'] }}
	</div>

	{{--  --}}
	<label>内容</label>
	<div>
		{{ $input['body'] }}
	</div>

	{{--  --}}
	<label>問い合わせの種類</label>
	<div>
		{{ $input['contact_type'] }}
	</div>

	{{--  --}}
	<label>メールアドレス</label>
	<div>
		{{ $input['email'] }}
	</div>

	{{--  --}}
	<label>年齢</label>
	<div>
		{{ $input['age'] }}歳
	</div>

	{{--  --}}
	<label>都道府県</label>
	<div>
		{{ $input['pref'] }}
	</div>

	{{-- 【20220906】画像追加 --}}
	@if($image)
	<label>画像</label>
	<div>
		{{ $image['file_path'] }}
		{{ Storage::url($image['file_path']) }}
		<img style="width:50px;" src="{{ Storage::url($image['file_path']) }}" />
	</div>
	@endif

	{{--  --}}
	<label>プライバシーポリシー</label>
	<div>
		{{ $input['agree'] }}
	</div>


	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>