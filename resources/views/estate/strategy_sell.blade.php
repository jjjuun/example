@extends('layouts.app')

@section('content')

@include("common.content-header")

    <form method="get" action="{{ url("/strategy_buy") }}">
        @csrf

        {{-- フォームの各nameのつけ方 --}}
        {{-- スネークケース --}}
        {{-- 確定：小文字、仮：大文字 --}}

            {{-- 売却 --}}
            <h2>3:strategy_sell</h2>
            <div class="card mb-3">
                <div class="card-header">売却</div>

                <div class="card-body row">
                    {{-- 収入 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">収入</div>

                        <div class="mb-3">
                            <label>（仮）物件売却費用（万円）</label>
                            <div>
                                <input type="number" name="KARI_SELL_PRICE" value="2000">
                            </div>
                        </div>
                    </div>

                    {{-- 支出 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">支出</div>

                        <div class="mb-3">
                            <label>仲介手数料率（％）</label>
                            <div>
                                <input type="number" name="sell_fee_rate" value="3">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>収入印紙代（円）</label>
                            <div>
                                <input type="number" name="sell_stamp_fee" value="20000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>その他（円）</label>
                            <div>
                                <input type="number" name="sell_other_fee" value="20000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <input class="btn btn-primary" type="submit" value="計算する" />
    </form>
@endsection