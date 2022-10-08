<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
【登録フォーム】
・フォームからの遷移先でセッションに入力値を保存
・確認画面の表示はセッションの入力値を使う
・確認がめんからの遷移先もセッションの入力値を使う
・送信処理（確認画面からの遷移先）で二重投稿にならないようにセッションの値を空にする
参考：https://note.com/laravelstudy/n/n1b82595e9fdd?magazine_key=me6288d51a1b8
*/

// １：get問い合わせフォームを表示
Route::get('/form', [App\Http\Controllers\SampleFormController::class, "show"])->name("form.show");

// ２：post問い合わせフォーム遷移先
Route::post('/form', [App\Http\Controllers\SampleFormController::class, "post"])->name("form.post");

// ３：get確認画面
Route::get('/form/confirm', [App\Http\Controllers\SampleFormController::class, "confirm"])->name("form.confirm");

// ４：post確認画面からフォーム遷移先
Route::post('/form/confirm', [App\Http\Controllers\SampleFormController::class, "send"])->name("form.send");

// ５：get完了画面
Route::get('/form/complete', [App\Http\Controllers\SampleFormController::class, "complete"])->name("form.complete");


/*
【検索フォーム】
*/
Route::get('/index', [App\Http\Controllers\SampleFormController::class, "index"])->middleware("auth");


/*
【ログイン機能】
*/
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
【ログイン後のフォーム投稿機能】
*/
// post問い合わせフォーム遷移先
Route::post('/home', [App\Http\Controllers\HomeController::class, 'post'])->name('home.post');

// get確認画面
Route::get('/home/confirm', [App\Http\Controllers\HomeController::class, "confirm"])->name("home.confirm");

// post確認画面からフォーム遷移先
Route::post('/home/confirm', [App\Http\Controllers\HomeController::class, "send"])->name("home.send");

// get完了画面
Route::get('/home/complete', [App\Http\Controllers\HomeController::class, "complete"])->name("home.complete");


/*
【相場チェック】
１：ユーザーがフォームで調べたい項目（取引時期、都道府県、市区町村など）を入力する
※API取得上は、取引時期、都道府県、市区町村のみ入力することになるが、実際にユーザーは、取引時期、都道府県、市区町村、築年、Type（中古マンションかどうか）、間取りで絞った上で検索し、m2単価が欲しい
２：検索に応じて返されたm2単価（TradePrice/Area）の値をViewに出力する
*/
Route::get('/api', [App\Http\Controllers\ApiController::class, 'form'])->name('getApi')->middleware("auth");
Route::get('/api/city', [App\Http\Controllers\ApiController::class, 'getCitys'])->name('getCitys')->middleware("auth");
Route::get('/api/DistrictName', [App\Http\Controllers\ApiController::class, 'getDistrictName'])->name('getDistrictName')->middleware("auth");

Route::post('/api', [App\Http\Controllers\ApiController::class, 'search'])->name('api.search')->middleware("auth");

/*
【出口戦略】
*/
// 購入
Route::get('/strategy_buy', [App\Http\Controllers\StrategyBuyController::class, 'strategyBuyShow'])->name('strategy_buy_show')->middleware("auth");
Route::post('/strategy_buy', [App\Http\Controllers\StrategyBuyController::class, 'strategyBuyPost'])->name('strategy_buy_post')->middleware("auth");
// 保有
Route::get('/strategy_possess', [App\Http\Controllers\StrategyPossessController::class, 'strategyPossessShow'])->name('strategy_posess_show')->middleware("auth");
Route::post('/strategy_possess', [App\Http\Controllers\StrategyPossessController::class, 'strategyPossessPost'])->name('strategy_posess_post')->middleware("auth");
// 売却
Route::get('/strategy_sell', [App\Http\Controllers\StrategySellController::class, 'strategySellShow'])->name('strategy_sell')->middleware("auth");


/*
【初期設定Viewファイル】
*/
Route::get('/', function () {
    return view('welcome');
});

/**
 * Estate_CRUD
 */
//Estate C
Route::get('/estate/create', [App\Http\Controllers\Estate\CreateController::class, 'form'])->name('estate.create');

Route::post('/estate/create/check', [App\Http\Controllers\Estate\CreateController::class, 'check'])->name('estate.create.check');

Route::get('/estate/create/confirm', [App\Http\Controllers\Estate\CreateController::class, 'confirm'])->name('estate.create.confirm');

Route::post('/estate/create/store', [App\Http\Controllers\Estate\CreateController::class, 'store'])->name('estate.create.store');

//Estate R
Route::get('/estate/index', [App\Http\Controllers\Estate\ListController::class, 'index'])->name('estate.index');

//Estate U
Route::get('/estate/edit/{id}', [App\Http\Controllers\Estate\ListController::class, 'edit'])->name('estate.edit');
Route::post('/estate/update/{id}', [App\Http\Controllers\Estate\ListController::class, 'update'])->name('estate.update');

//Estate D
Route::post('/estate/delete/{id}', [App\Http\Controllers\Estate\ListController::class, 'delete'])->name('estate.delete');

/**
 * User_CRUD
 */
// User C：メールアドレス、パスワードでユーザー登録する＝Createとし、別途Routeは設けない。

// User R（Uを作ってからにする）
Route::get('/user/index', [App\Http\Controllers\User\UserController::class, 'index'])->name('user.index');

// User U
// プロフィール
Route::get('/user/edit/{id}', [App\Http\Controllers\User\UserController::class, 'edit'])->name('user.edit');
Route::post('/user/update/{id}', [App\Http\Controllers\User\UserController::class, 'update'])->name('user.update');

// パスワード
Route::get('/user/password/edit/{id}', [App\Http\Controllers\User\UserController::class, 'editPassword'])->name('password.edit');
Route::post('/user/password/edit/{id}', [App\Http\Controllers\User\UserController::class, 'updatePassword'])->name('password.update');

// User D


// 認証
/**
 * 全体
 */
Route::get('/register_all', [App\Http\Controllers\Auth\RegisterAllController::class, 'register_all'])->name('register_all');
Route::get('/login_all', [App\Http\Controllers\Auth\LoginAllController::class, 'login_all'])->name('login_all');

/**
 * Github
 */
// 「Githubでアカウント登録」をクリックすると以下のディレクトリにアクセスする。
Route::get('/auth/github', [App\Http\Controllers\Auth\GithubAuthController::class, 'login'])->name('github.auth.login');
Route::get('/auth/github/callback', [App\Http\Controllers\Auth\GithubAuthController::class, 'callback'])->name('github.auth.callback');

/**
 * Google
 */
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirectToGoogle'])->name('google.auth.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])->name('google.auth.callback');



// ↓↓↓【20221003】パスワードリセット機能作成のため追加（※レンタルサーバー使用時に完成させる）↓↓↓
Route::get('/forget-password', [App\Http\Controllers\User\UserController::class, 'requestResetPassword'])->middleware("guest")->name('password.request');
Route::post('/forget-password', [App\Http\Controllers\User\UserController::class, 'sendResetNotification'])->middleware("guest")->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\User\UserController::class, 'sendResetPasswordForm'])->middleware("guest")->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\User\UserController::class, 'resetPassword'])->middleware("guest")->name('password.update');
// ↑↑↑【20221003】パスワードリセット機能作成のため追加（※レンタルサーバー使用時に完成させる）↑↑↑




