@extends('layouts.app')

@section('content')

@include("common.content-header")

    <form method="post" action="{{ url("/strategy_possess") }}">
        @csrf

        {{-- フォームの各nameのつけ方 --}}
        {{-- スネークケース --}}
        {{-- 確定：小文字、仮：大文字 --}}

            {{-- 保有 --}}
            <h2>2:strategy_possess</h2>
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

        <input class="btn btn-primary" type="submit" value="計算する" />
    </form>
@endsection