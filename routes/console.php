<?php

use App\Models\Message;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    // Role::create([//создание модели
    //         'name' => 'Админ',
    //     ]);
    //     Schema::table('', function (Blueprint $table) {
    //         $table
    //             ->foreignId('role_id')
    //             ->change();
    //         });
        // $user = User::find(1);
        // $user->roles()->attach(1);
        // $role = Role::where('name','Пользователь')->first();
    // Message::create([
    //     'user_id' => 1,
    //     'author' => 'user',
    //     'message' => 'Welcome test',
    // ]);
    // $user = Auth::user();
    $checkingDate = Message::where('id', 88)->first()->created_at;
    $current = Carbon::now();
    $res = $checkingDate->diffInHours($current);
    dd($res);
})->purpose('Display an inspiring quote');
