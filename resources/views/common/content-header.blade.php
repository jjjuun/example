<div class="card mb-3">
    {{-- <div class="card-header"></div> --}}

    <div class="card-body">

        <div class="row text-center">
            <div class="col-3">
                <a href="{{ route('estate.create') }}">マイ物件登録</a>
            </div>

            <div class="col-3">
                <a href="{{ route('estate.index') }}">マイ物件一覧</a>
            </div>

            <div class="col-3">
                <a href="{{ route('getApi') }}">相場チェック</a>
            </div>

            <div class="col-3">
                <a href="{{ route('strategy_buy_show') }}">出口戦略チェック</a>
            </div>
        </div>
    </div>
</div>