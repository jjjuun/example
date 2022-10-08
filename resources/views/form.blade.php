<h3>登録フォーム</h3>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@guest
<p>ログインしていない時</p>
@endguest

@auth
<p>ログインしている時</p>
{{ Auth::id() }}

{{ Auth::user()->name }}
@endauth

<form method="post" action="{{ url('/form') }}" enctype="multipart/form-data">
	@csrf

    {{--  --}}
	<label>名前</label>
	<div>
		<input type="text" name="name" value="{{ old('name', @$input["name"]) }}" />
	</div>

    {{--  --}}
    <label>なまえ</label>
	<div>
		<input type="text" name="name_kana" value="{{ old('name_kana') }}" />
	</div>

    {{--  --}}
	<label>件名</label>
	<div>
		<input type="text" name="title" value="{{ old('title') }}" />
	</div>

    {{--  --}}
	<label for="input_body">内容</label>
	<div>
		<textarea id="input_body" name="body" value="">{{ old('body') }}</textarea>
	</div>

    {{--  --}}
    <label>問い合わせの種類</label>
	<div>
		<input id="contact_type_01" type="radio" name="contact_type" value="商品について" checked>
        <label for="contact_type_01">商品について</label>
		<input id="contact_type_02" type="radio" name="contact_type" value="店舗について">
        <label for="contact_type_02">店舗について</label>
	</div>

    {{--  --}}
    {{-- 一通り、フォームができたら後で確認用メールアドレスを登録するフォームを追加する --}}
    <label>メールアドレス</label>
    <div>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="例）aaa@example.com">
	</div>

    {{--  --}}
    <label>年齢</label>
    <div>
        <input type="number" name="age" value="{{ old('age') }}">歳
    </div>

    {{--  --}}
    <label>都道府県</label>
    <div>
        <select name="pref">
            <option value="" selected>-----</option>
            <option value="北海道">北海道</option>
            <option value="青森県">青森県</option>
            <option value="岩手県">岩手県</option>
            <option value="宮城県">宮城県</option>
            <option value="秋田県">秋田県</option>
            <option value="山形県">山形県</option>
            <option value="福島県">福島県</option>
            <option value="茨城県">茨城県</option>
            <option value="栃木県">栃木県</option>
            <option value="群馬県">群馬県</option>
            <option value="埼玉県">埼玉県</option>
            <option value="千葉県">千葉県</option>
            <option value="東京都">東京都</option>
            <option value="神奈川県">神奈川県</option>
            <option value="新潟県">新潟県</option>
            <option value="富山県">富山県</option>
            <option value="石川県">石川県</option>
            <option value="福井県">福井県</option>
            <option value="山梨県">山梨県</option>
            <option value="長野県">長野県</option>
            <option value="岐阜県">岐阜県</option>
            <option value="静岡県">静岡県</option>
            <option value="愛知県">愛知県</option>
            <option value="三重県">三重県</option>
            <option value="滋賀県">滋賀県</option>
            <option value="京都府">京都府</option>
            <option value="大阪府">大阪府</option>
            <option value="兵庫県">兵庫県</option>
            <option value="奈良県">奈良県</option>
            <option value="和歌山県">和歌山県</option>
            <option value="鳥取県">鳥取県</option>
            <option value="島根県">島根県</option>
            <option value="岡山県">岡山県</option>
            <option value="広島県">広島県</option>
            <option value="山口県">山口県</option>
            <option value="徳島県">徳島県</option>
            <option value="香川県">香川県</option>
            <option value="愛媛県">愛媛県</option>
            <option value="高知県">高知県</option>
            <option value="福岡県">福岡県</option>
            <option value="佐賀県">佐賀県</option>
            <option value="長崎県">長崎県</option>
            <option value="熊本県">熊本県</option>
            <option value="大分県">大分県</option>
            <option value="宮崎県">宮崎県</option>
            <option value="鹿児島県">鹿児島県</option>
            <option value="沖縄県">沖縄県</option>
        </select>
    </div>

    {{-- 【20220906】画像 --}}
    <div>
        <input type="file" name="image" accept="image/png, image/jpeg">
    </div>
        
    {{--  --}}
    <label>プライバシーポリシー</label>
    <div>
        <input type="checkbox" name="agree" value="同意する">同意する
    </div>

    {{--  --}}
	<input class="btn btn-primary" type="submit" value="送信" />
</form>

