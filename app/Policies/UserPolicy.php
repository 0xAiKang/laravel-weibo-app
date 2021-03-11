<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * 为update 方法添加授权策略
     * @param User $current_user        当前用户
     * @param User $user                授权用户
     * @return bool
     */
    public function update(User $current_user, User $user)
    {
        return $current_user->id === $user->id;
    }

    public function destroy(User $current_user, User $user)
    {
         return $current_user->is_admin && $current_user->id !== $user->id;
    }
}
