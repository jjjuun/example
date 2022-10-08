@extends('layouts.app')

@section('content')

@include("common.content-header")

    <form method="get" action="{{ url("/strategy_buy") }}">
        @csrf

        {{-- フォームの各nameのつけ方 --}}
        {{-- スネークケース --}}
        {{-- 確定：小文字、仮：大文字 --}}

            {{-- 購入 --}}
            <h2>strategy_buy</h2>
            <div class="card mb-3">
                <div class="card-header">購入</div>
                
                <div class="card-body row">
                    {{-- 収入 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">ローン</div>
                        {{-- ローンの部分はアコーディオンで詳細を開閉できるようにする --}}

                        <div class="mb-3">
                            <label>不動産ローン（万円）</label>

                            <div>
                                <input type="number" name="loan" value="4000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>自己資金（万円）</label>

                            <div>
                                <input type="number" name="loan_own_resource" value="500">
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
                                    <option value="元利均等返済">元利均等返済</option>
                                    <option value="元金均等返済">元金均等返済</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済期間（年）</label>

                            <div>
                                <input type="number" name="loan_repayment_duration" value="35">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始（年）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_year" value="2016">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始（月）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_month" value="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>返済開始時の年齢（歳）</label>

                            <div>
                                <input type="number" name="loan_repayment_start_age" value="30">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>金利方式</label>
                            {{-- 固定金利 or 変動金利 --}}
                            {{-- とりあえず、固定金利で動くようにする。出口戦略の開発が一通りできたら変動金利を選択した時の開発を進める --}}
                            <div>
                                <select name="loan_interest_rate_method">
                                    <option value="">選択してください</option>
                                    <option value="固定金利">固定金利</option>
                                    <option value="変動金利">変動金利</option>
                                </select>
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
                                        <option value="{{$key}}" @if($myEstates == request()->get("myEstates")) selected @endif>{{$myEstate["EstateName"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>仲介手数料率（％）</label>
                            <div>
                                <input type="number" name="buy_brokerage_rate" value="3">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>収入印紙代（円）</label>
                            <div>
                                <input type="number" name="buy_stamp_fee" value="20000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>登記費用（円）</label>
                            <div>
                                <input type="number" name="buy_registration_fee" value="50000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>その他（円）</label>
                            <div>
                                <input type="number" name="buy_other_fee" value="50000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 保有 --}}
            <h2>strategy_possess</h2>
            <div class="card mb-3">
                <div class="card-header">保有</div>

                <div class="card-body row">
                    {{-- 収入 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">収入</div>

                        <div class="mb-3">
                            <label>家賃収入（円/月）</label>
                            {{-- 経年変化を表現したい --}}
                            <div>
                                <input type="text" name="property_income" value="150000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>還付金（円/年）</label>
                            {{-- 経年変化を表現したい --}}
                            <div>
                                <input type="text" name="refund" value="100000">
                            </div>
                        </div>
                    </div>

                    {{-- 支出 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">支出</div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">物件保有</div>
                            <div class="mb-3">
                                <label>管理費（円/月）</label>
                                {{-- 経年変化を表現したい --}}
                                <div>
                                    <input type="text" name="property_management_cost" value="10000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>修繕積立費（円/月）</label>
                                {{-- 経年変化を表現したい --}}
                                <div>
                                    <input type="text" name="perperty_maintenance_cost" value="10000">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">ローン</div>
                            <div class="mb-3">
                                <label>（仮）月間ローン返済額（円/月）</label>
                                {{-- もろもろの条件を入れてアプリで計算できるようにする --}}
                                <div>
                                    <input type="text" name="KARI_LOAN" value="100000">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">税</div>
                            {{-- 税はマイ物件から概算は算出できるので、マイ物件（estatesテーブル）に格納する --}}
                            {{-- 本フォームのvalueには、estatesテーブルから値を取得する --}}

                            <div class="mb-3">
                                <label>（仮）固定資産税（円/年）</label>
                                {{-- もろもろの条件を入れてアプリで計算できるようにする --}}
                                <div>
                                    <input type="text" name="KARI_PROPERTY_TAX" value="100000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>（仮）都市計画税（円/年）</label>
                                {{-- もろもろの条件を入れてアプリで計算できるようにする --}}
                                <div>
                                    <input type="text" name="KARI_CITY_PLAN_TAX" value="100000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>（仮）所得税（円/年）</label>
                                {{-- もろもろの条件を入れてアプリで計算できるようにする --}}
                                <div>
                                    <input type="text" name="KARI_INCOME_TAX" value="100000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>（仮）住民税（円/年）</label>
                                {{-- もろもろの条件を入れてアプリで計算できるようにする --}}
                                <div>
                                    <input type="text" name="KARI_RESIDENT_TAX" value="100000">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">税</div>

                            <div class="mb-3">
                                <label>年間火災保険料（円/年）</label>
                                <div>
                                    <input type="number" name="fire_insurance" value="50000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>年間地震保険料（円/年）</label>
                                <div>
                                    <input type="number" name="erthquake_insurance" value="50000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>年間その他保険料（円/年）</label>
                                <div>
                                    <input type="number" name="other_insurance" value="50000">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">その他</div>

                            <div class="mb-3">
                                <label>個別修繕費（円）※頻度は以下で指定</label>
                                <div>
                                    <input type="number" name="repair_cost" value="10">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>上記が発生する頻度</label>
                                <div>
                                    <input type="number" name="repair_frequency" value="3">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>立ち退き費用（万円）※頻度は以下で指定</label>
                                <div>
                                    <input type="number" name="eviction_cost" value="50">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>立ち退きが発生する頻度</label>
                                <div>
                                    <input type="number" name="eviction_frequency" value="1">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>その他（円）※頻度は以下で指定</label>
                                <div>
                                    <input type="number" name="other_cost" value="100000">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>その他が発生する頻度</label>
                                <div>
                                    <input type="number" name="other_frequency" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 売却 --}}
            <h2>strategy_sell</h2>
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