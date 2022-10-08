
@if(isset($float_unitPrice))
    <p>相場チェックの結果：{{ $float_unitPrice }}万円/m2</p>
@endif

{{-- @if($responses)
    <h3>APIから直接取ったデータ</h3>
    <p>{{ $responses }}</p>
@endif --}}

@if(isset($new_selected_estates))
    <h3>API取得後、フォームで検索したデータ</h3>

    @foreach($new_selected_estates as $new_selected_estate)
    <p>---------</p>
        <p>物件種目：{{ $new_selected_estate["Type"] }}</p>
        <p>市区町村：{{ $new_selected_estate["Municipality"] }}</p>
        <p>間取り：{{ $new_selected_estate["FloorPlan"] }}</p>
        <p>地区名：{{ $new_selected_estate["DistrictName"] }}</p>
        <p>築年：{{ $new_selected_estate["BuildingYear"] }}</p>
        <p>価格：{{ $new_selected_estate["TradePrice"] }}</p>
        <p>面積：{{ $new_selected_estate["Area"] }}</p>
    @endforeach
@endif

