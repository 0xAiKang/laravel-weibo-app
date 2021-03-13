<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function __construct()
    {
        // 通过中间件限流
        $this->middleware("throttle:2,1", [
            "only" => ["showLinkRequestForm"]
        ]);

        $this->middleware('throttle:3,10', [
            'only' => ['sendResetLinkEmail']
        ]);
    }

    /**
     * Email 表单
     */
    public function showLinkRequestForm()
    {
        return view("auth.password");
    }

    public function sendResetLinkEmail(Request $request)
    {
        // 验证邮箱
        $this->validate($request, [
            "email" => "required|email"
        ]);
        $email = $request->email;

        // 查找用户
        $user = User::where("email", $email)->first();

        // 如果没有找到，返回提示
        if (is_null($user)) {
            session()->flash("danger", "邮箱未找到");
            return redirect()->back()->withInput();
        }

        // 生成Token
        $token = hash_hmac("sha256", Str::random(50), config("app.key"));

        // 入库（使用updateOrInsert 保持Email 唯一
        DB::table("password_resets")->updateOrInsert(["email" => $email], [
            "email" => $email,
            "token" => Hash::make($token),
            "created_at" => new Carbon(),
        ]);

        // 将Token 发送给用户
        Mail::send("emails.reset_link", compact("token"), function ($message) use ($email){
            $message->to($email)->subject("忘记密码");
        });

        // 提示操作成功
        session()->flash("success", "重置密码邮件已发送，请注意查收");
        return redirect()->back();
    }

    /**
     * 显示重置密码表单
     * @param $token
     */
    public function showResetForm($token)
    {
//        var_dump($token);
        return view("auth.passwords.reset", compact("token"));
    }

    public function reset(Request $request)
    {
        // 验证数据是否合规
        $this->validate($request, [
            "email" => "required|email",
            "token" => "required",
            "password" => "required|confirmed|min:6",
        ]);
        /*$email = $request->email;
        $token = $request->token;
        $password = $request->password;*/

        [
            "email" => $email,
            "token" => $token,
            "password" => $password,
        ] = $request;

        // 找回密码链接的有效时间
        $expires = 60 * 10;

        // 获取对应用户
        $user = User::where("email", $email)->first();

        // 如果不存在
        if (is_null($user)) {
            session()->flash("danger", "未找到该用户");
            return redirect()->back()->withInput();
        }

        // 读取重置记录
        // 注意：因为password_resets这张表没有建模，所以这里只能用 DB 去操作
        $record = (array)DB::table("password_resets")->where("email", $email)->first();

        if ($record) {
            // 检查是否过期
            if (Carbon::parse($record['created_at'])->addSeconds($expires)->isPast()) {
                session()->flash("danger", "链接已经过期");
                return redirect()->back();
            }

            // 检查是否正确
            if (!Hash::check($token, $record['token'])) {
                session()->flash("danger", "令牌错误");
                return redirect()->back();
            }

            // 更新用户密码（以下四种写法都是正确的
            // $user->update(["password" => bcrypt($password)]);
            // $user->password = bcrypt($password);
            // $user->save();
            /*User::where("id", $user->id)->update([
                "password" => bcrypt($password)
            ]);*/

            DB::table("users")->where("id", $user->id)->update([
                "password" => bcrypt($password),
            ]);

            // 提示用户更新成功，重新登录
            session()->flash("success", "重置成功，请重新登录");
            Auth::logout();
            return redirect()->route("login");
        }

        session()->flash("danger", "未找到重置记录");
        return redirect()->back();
    }
}
