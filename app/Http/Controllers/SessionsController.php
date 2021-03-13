<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // guest 用于指定未登录用户访问的动作
        $this->middleware("guest", [
            "only" => "create",
        ]);

        // 限流 10 分钟十次
        $this->middleware('throttle:10,10', [
            'only' => ['store']
        ]);
    }

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
            if (Auth::user()->activated) {
                session()->flash("success", "欢迎回来！");
//            var_dump(Auth::user());
                $fallback = route("users.show", Auth::user()->id);
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash("warning", "你的账号尚未激活，请检查邮箱中的注册链接并激活");
                return redirect("/");
            }
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
