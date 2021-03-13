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

// 定义路由
Route::get("/", "StaticPagesController@home")->name('home');
Route::get("/help", "StaticPagesController@help")->name('help');
Route::get("/about", "StaticPagesController@about")->name("about");

// 用户注册
Route::get("signup", "UsersController@create")->name("signup");

//Route::resource("users", "UsersController");

// 等同于以下代码
// 用户列表
Route::get('/users', 'UsersController@index')->name('users.index');
// 注册页面
Route::get('/users/create', 'UsersController@create')->name('users.create');
// 显示用户信息
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
// 注册用户
Route::post('/users', 'UsersController@store')->name('users.store');
// 编辑用户
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
// 更新用户
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
// 删除用户
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');

// 会话管理
// 显示登录页面
Route::get('login', 'SessionsController@create')->name('login');
// 创建新会话（登录）
Route::post('login', 'SessionsController@store')->name('login');
// 销毁会话（退出登录）
Route::delete('logout', 'SessionsController@destory')->name('logout');

// 验证token
Route::get("signup/confirm/{token}", "UsersController@confirmEmail")->name("confirm_email");
// 忘记密码页面—— 填写 Email 的表单
Route::get("password/reset", "PasswordController@showLinkRequestForm")->name("password.request");
// 发送邮件——处理表单提交，成功的话就发送邮件，附带 Token 的链接
Route::post("password/email", "PasswordController@sendResetLinkEmail")->name("password.email");
// 忘记密码token——显示更新密码的表单，包含 token
Route::get("password/{token}", "PasswordController@showResetForm")->name("password.reset");
// 重置密码——对提交过来的 token 和 email 数据进行配对，正确的话更新密码
Route::post("password/reset", "PasswordController@reset")->name("password.update");


//Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
// 创建微博
Route::post("/statuses", "StatusesController@store")->name("statuses.store");
// 删除微博
Route::delete("/statuses/{status}", "StatusesController@destroy")->name("statuses.destroy");
