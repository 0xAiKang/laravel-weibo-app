<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 定义删除策略
     * @param User $user
     * @param Status $status
     * @return bool
     */
    public function destroy(User $user, Status $status)
    {
        // 当前用户id 等于发布微博id
        return $user->id === $status->user_id;
    }
}
