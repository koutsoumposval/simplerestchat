<?php
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class UsersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Demo user 1';
        $user->email = 'user1@demo.com';
        $user->password = Hash::make('user1pass');
        $user->save();

        $user = new User;
        $user->name = 'Demo user 2';
        $user->email = 'user2@demo.com';
        $user->password = Hash::make('user2pass');
        $user->save();
    }
}
