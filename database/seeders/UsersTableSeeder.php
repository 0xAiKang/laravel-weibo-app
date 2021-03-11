<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成50 个假用户
        User::factory()->count(50)->create();

        $user = User::find(1);
        $user->name = "boo";
        $user->email = "boo@example.com";
        $user->password = bcrypt("122410");
        $user->is_admin = true;
        $user->save();
    }
}
