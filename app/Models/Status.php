<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    // 允许正常更新该字段
    protected $fillable = ['content'];

    /**
     * 指明一条微博属于一个用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // 这里的User::class 表示完全限定名称 App\Models\User
        // status 表反向关联 user 表
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
