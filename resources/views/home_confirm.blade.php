<h3>home_confirm.blade.php</h3>
<form method="post" action="{{ route('home.send') }}">
	@csrf

	{{--  --}}
	<label>user_id</label>
	<div>
		{{ $input["user_id"] }}
	</div>

	{{--  --}}
	<label>comment</label>
	<div>
		{{ $input["comment"] }}
	</div>

	<input name="back" type="submit" value="戻る" />
	<input name="submit" type="submit" value="送信" />

</form>