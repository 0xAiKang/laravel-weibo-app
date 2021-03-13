<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        // 添加中间件过滤请求
        $this->middleware("auth");
    }

    /**
     * 发布微博
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "content" => "required|max:140",
        ]);

        // $user->statuses()->create() 通过这种方式创建会自动把微博和用户进行关联
        Auth::user()->statuses()->create([
            "content" => $request["content"],
        ]);

        session()->flash("success", "发布成功");
        return redirect()->back();
    }

    /**
     * 删除微博
     * @param Status $status
     */
    public function destroy(Status $status)
    {
        // 验证删除策略
        $this->authorize("destroy", $status);

        // 调用 Eloquent 模型的 delete 方法对该微博进行删除。
        $status->delete();
        session()->flash("success", "删除成功");
        return redirect()->back();
    }
}
