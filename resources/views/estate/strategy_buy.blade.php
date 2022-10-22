@extends('layouts.app')

@section('content')

@include("common.content-header")

    <form method="post" action="{{ url("/strategy_buy") }}">
        @csrf

        {{-- フォームの各nameのつけ方 --}}
        {{-- スネークケース --}}
        {{-- 確定：小文字、仮：大文字 --}}

            {{-- 購入 --}}
            <h2>1:strategy_buy</h2>
            <div class="card mb-3">
                <div class="card-header">購入</div>
                
                <div class="card-body row">
                    {{-- 収入 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">ローン</div>
                        {{-- ローンの部分はアコーディオンで詳細を開閉できるようにする --}}

                        <div class="mb-3">
                            <label>不動産購入価格（万円）</label>

                            <div>
                                <input type="number" name="buy_price" value="{{ old("buy_price", "4000") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>自己資金（万円）</label>

                            <div>
                                <input type="number" name="loan_own_resource" value="{{ old("loan_own_resource", "1000") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済方式</label>
                            {{-- 元利均等返済 or 元金均等返済 --}}
                            {{--  --}}
                            <div>
                                <a href="https://finance.recruit.co.jp/article/k025/" target="_blank">元利均等返済と元金均等返済の違い</a>
                            </div>

                            <div>
                                <a href="https://keisan.casio.jp/exec/system/1256183644" target="_blank">ローン検算用</a>
                            </div>

                            <div>
                                <a href="https://mponline.sbi-moneyplaza.co.jp/housingloan/articles/20200903risokukeisan.html" target="_blank">ローン計算式参考１</a>
                            </div>

                            <div>
                                <a href="https://www.a-tm.co.jp/top/housingloan/basicknowledges/type/housing-loane-a_formula/" target="_blank">ローン計算式参考２</a>
                            </div>



                            <div>
                                <select name="loan_repayment_method">
                                    <option value="">選択してください</option>
                                    <option value="元利均等返済" @if( old('loan_repayment_method') === '元利均等返済' ) selected @endif>元利均等返済</option>
                                    <option value="元金均等返済" @if( old('loan_repayment_method') === '元金均等返済' ) selected @endif>元金均等返済</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済期間（年）</label>

                            <div>
                                <input type="number" name="loan_repayment_duration" value="{{ old("loan_repayment_duration", "35") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始（年）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_year" value="{{ old("loan_repayment_start_year", "2016") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始（月）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_month" value="{{ old("loan_repayment_start_month", "10") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始時の年齢（歳）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_age" value="{{ old("loan_repayment_start_age", "30") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>金利方式</label>
                            {{-- 固定金利 or 変動金利 --}}
                            {{-- とりあえず、固定金利で動くようにする。出口戦略の開発が一通りできたら変動金利を選択した時の開発を進める --}}
                            <div>
                                <select name="loan_interest_rate_method">
                                    <option value="">選択してください</option>
                                    <option value="固定金利" @if( old('loan_interest_rate_method') === '固定金利' ) selected @endif>固定金利</option>
                                    <option value="変動金利" @if( old('loan_interest_rate_method') === '変動金利' ) selected @endif>変動金利</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>ローン返済年利（％）</label>

                            <div>
                                <input type="number" step="0.1" name="loan_interest_rate" value="{{ old("loan_interest_rate", "1.0") }}">
                            </div>
                        </div>
                    </div>

                    {{-- 支出 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">支出</div>

                        <div class="mb-3">
                            <label>マイ物件を選択する</label>
                            <div>
                                <select name="my_estate">
                                    <option value="">選択してください</option>

                                    @foreach($myEstates as $key => $myEstate)
                                        <option value="{{$myEstate["EstateName"]}}" @if($myEstate["EstateName"] === old("my_estate")) selected @endif>{{$myEstate["EstateName"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>仲介手数料率（％）</label>
                            <div>
                                <input type="number" name="buy_brokerage_rate" value="{{ old("buy_brokerage_rate", "3") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>収入印紙代（円）</label>
                            <div>
                                <input type="number" name="buy_stamp_fee" value="{{ old("buy_stamp_fee", "20000") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>登記費用（円）</label>
                            <div>
                                <input type="number" name="buy_registration_fee" value="{{ old("buy_registration_fee", "50000") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>その他（円）</label>
                            <div>
                                <input type="number" name="buy_other_fee" value="{{ old("buy_other_fee", "50000") }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <input class="btn btn-primary" type="submit" value="計算する" />
    </form>
@endsection