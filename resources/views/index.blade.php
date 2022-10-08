
<!-- データベース検索フォーム -->
<h3>データベース検索フォーム</h3>
<form method="get" action="">
    <div>
        {{-- id、名前、なまえ --}}
        <p>id、名前、なまえのいずれかを入力してください</p>
        <select name="check_kind_one">
            <option value="id" >id</option>
            <option value="name" @if("name" == request()->get('check_kind_one')) selected @endif>名前</option>
            <option value="name_kana">なまえ</option>
        </select>
        <input type="text" name="keywords" value="{{  request()->get('keywords') }}">
        <br>
        <p>県を選択してください。</p>
        <div>
            <select name="pref_result">
                <option value="">選択してください</option>
                @foreach($prefectures as $key => $pref)
                <option value="{{$pref}}" @if($pref == request()->get('pref_result')) selected @endif>{{$key}}:{{$pref}}</option>
                @endforeach
    
            </select>
        </div>
        <br>
        <input type="submit" value="検索">
    </div>
</form>

<p>検索結果</p>
@foreach($results as $result)
    <p>id:{{ $result->id }},名前:{{ $result->name }},email:{{ $result->email }},県:{{ $result->pref }}</p>
@endforeach


{{-- データベースの内容をidとnameのみ一覧表示する --}}
<h3>データベース一覧</h3>
@foreach($contacts as $contact)
    <p>id:{{ $contact["id"] }},名前:{{ $contact["name"] }},メール:{{ $contact["email"] }},年齢:{{ $contact["age"] }}</p>
@endforeach

<p>{{ $contacts->links('pagination::bootstrap-4') }}</p>


{{-- 以下のコードで、検索フォームに入力した値が出力されているので、Controllerに変数keywordsは渡されているは確認できた。 --}}
{{-- <p>{{ $keywords }}</p> --}}
