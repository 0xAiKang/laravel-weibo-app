<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        // 使用中间件
        $this->middleware("auth", [
            // 指定这几个方法不使用Auth 去验证
            // 默认都需要访问权限，在这个指定之后，就不需要了
            "except" => ["show", "create", "store", "index", "confirmEmail"]
        ]);

        $this->middleware("guest", [
            "only" => "create",
        ]);
    }

    public function index()
    {
        $users = User::paginate(6);
        return view("users.index", compact('users'));
    }

    /**
     * 注册页面
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

        $this->sendEmailConfirmationTo($user);
        // Auth::login($user);
        session()->flash('success', "邮件已发送，请注意查收");
        return redirect("/");
    }

    public function edit(User $user)
    {
        // 进行授权验证
        $this->authorize("update", $user);
//        var_dump($user);
        return view("users.edit", compact("user"));
    }

    /**
     * 更新用户资料
     * @param Request $request
     * @param User $user
     */
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

    public function destroy(User $user)
    {
        // 添加验证权限
        $this->authorize("destroy", $user);
        $user->delete();
        session()->flash("success", "删除成功");
        return back();
    }

    /**
     * 确认用户邮件
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        // 查找用户
        // firstOrFail 取出第一个用户，如果查询不到指定用户返回404
        $user = User::where("activation_token", $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        // 用户登录
        Auth::login($user);
        session()->flash("success", "恭喜，激活成功");
        return redirect()->route("users.show", $user->id);
    }

    /**
     * 发送邮件
     * @param $user
     */
    protected function sendEmailConfirmationTo($user)
    {
        // 邮箱视图模版
        $views = "emails.confirm";
        $data = compact("user");
        $from = "geeek001@qq.com";
        $name = "boo";
        $to = $user->email;
        $subject = "感谢注册";

        Mail::send($views, $data, function ($message) use ($from, $name, $to, $subject){
            $message->to($to)->subject($subject);
        });
    }
}
