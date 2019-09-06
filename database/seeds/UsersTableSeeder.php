<?php

use Illuminate\Database\Seeder;
use \App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Phil';
        $user->email = 'example@example.com';
        $user->password = bcrypt('123123qweqwe');
        $user->avatar_url = Storage::url('no-avatar.png');
        $user->username = 'freezabb';
        $user->save();

        $user->assignRole('admin', 'moderator', 'user');
    }
}
