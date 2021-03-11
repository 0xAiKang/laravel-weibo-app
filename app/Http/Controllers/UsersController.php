<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        // 使用中间件
        $this->middleware("auth", [
            // 指定这几个方法不使用Auth 去验证
            "except" => ["show", "create", "store"]
        ]);

        $this->middleware("guest", [
            "only" => "create",
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        // 表示返回 users/create 页面
        return view("users.create");
    }

    /**
     * 显示个人中心
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        return view("users.show", compact("user"));
    }

    /**
     * 用户注册
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 验证数据合法性
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:225',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', "欢迎，您将在这里开启一段新的旅程~");
        return redirect()->route("users.show", [$user->id]);
    }

    public function edit(User $user)
    {
        // 进行授权验证
        $this->authorize("update", $user);
//        var_dump($user);
        return view("users.edit", compact("user"));
    }

    public function update(Request $request, User $user)
    {
        // 进行授权验证
        $this->authorize("update", $user);
        // 验证数据
        $this->validate($request, [
            "name" => "required|max:50",
            "password" => "nullable|confirmed|min:6"
        ]);

        $data = [];
        $data["name"] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        // 直接通过User 模型
        $user->update($data);
        session()->flash("success", "更新资料成功！");
        return redirect()->route("users.show", $user->id);
    }
}
