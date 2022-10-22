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
                                <input type="text" name="property_income" value="{{ old("property_income", "150000") }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>還付金（円/年）</label>
                            {{-- 経年変化を表現したい --}}
                            <div>
                                <input type="text" name="refund" value="{{ old("refund", "100000") }}">
                            </div>
                        </div>
                    </div>

                    {{-- 支出 --}}
                    <div class="col-6">
                        <div class="card text-white bg-primary mb-3 text-center">支出</div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">物件保有</div>
                            <div class="mb-3">
                                <label>物件管理による経費（円）</label>
                                <div>
                                    <input type="text" name="property_possess_expense" value="{{ old("property_possess_expense", "10000") }}">
                                </div>
                                <p>ローン金利、管理費、修繕費、火災保険料、地震保険料、固定資産税、都市計画税、減価償却費は別途入力するため不要です。</p>
                                <p>上記以外の家賃収入を得るために使った交際費、交通費などの年間総額を入力してください。</p>
                            </div>
                        </div>

                        {{-- 固定資産税、都市計画税はマイ物件から取得、所得税、住民税はControllerで計算しているため入力フォームを設定しない --}}

                        <div class="card mb-3">
                            <div class="card-header mb-3">保険</div>

                            <div class="mb-3">
                                <label>年間火災保険料（円/年）</label>
                                <div>
                                    <input type="number" name="fire_insurance" value="{{ old("fire_insurance", "30000") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>年間地震保険料（円/年）</label>
                                <div>
                                    <input type="number" name="erthquake_insurance" value="{{ old("erthquake_insurance", "5000") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>年間その他保険料（円/年）</label>
                                <div>
                                    <input type="number" name="other_insurance" value="{{ old("other_insurance", "50000") }}">
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header mb-3">その他</div>

                            <div class="mb-3">
                                <label>個別修繕費（円/回）</label>
                                <div>
                                    <input type="number" name="repair_cost" value="{{ old("repair_cost", "50000") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>個別修繕開始年</label>
                                <div>
                                    <input type="number" name="repair_start" value="{{ old("repair_cost_start", "2019") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>個別修繕の頻度</label>
                                <div>
                                    <input type="number" name="repair_frequency" value="{{ old("repair_frequency", "3") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>立ち退き費用（円/回）</label>
                                <div>
                                    <input type="number" name="eviction_cost" value="{{ old("eviction_cost", "50000") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>立ち退き開始年</label>
                                <div>
                                    <input type="number" name="eviction_start" value="{{ old("eviction_start", "2019") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>立ち退きの頻度</label>
                                <div>
                                    <input type="number" name="eviction_frequency" value="{{ old("eviction_frequency", "3") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>その他（円/回）</label>
                                <div>
                                    <input type="number" name="other_cost" value="{{ old("other_cost", "50000") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>その他開始年</label>
                                <div>
                                    <input type="number" name="other_start" value="{{ old("other_start", "2019") }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>その他の頻度</label>
                                <div>
                                    <input type="number" name="other_frequency" value="{{ old("other_frequency", "3") }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <input class="btn btn-primary" type="submit" value="計算する" />
    </form>
@endsection