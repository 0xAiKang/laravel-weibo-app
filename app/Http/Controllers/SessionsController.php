<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    /**
     * 显示损户登录页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 返回用户表单信息
        $login_info = $this->validate($request, [
            'email' => 'required|email|max:225',
            'password' => 'required'
        ]);
        if (Auth::attempt($login_info, $request->has('remember'))) {
            session()->flash("success", "欢迎回来！");
//            var_dump(Auth::user());
             return redirect()->route('users.show', [Auth::user()->id]);
        } else {
            session()->flash("danger", "很抱歉，您的邮箱和密码不匹配");
            return redirect()->back()->withInput();
        }
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory()
    {
        // 退出登录
        Auth::logout();
        session()->flash("success", "您已成功退出！");
        return redirect()->route("login");
    }
}