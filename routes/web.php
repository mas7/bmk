<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/client');

// Route::get('/email', function () {
//     $data = (object)[
//         'name' => 'Ahmed',
//         'email' => 'ahmed@gmail.com',
//         'phone_number' => '01020081375',
//         'password' => 'ahmed@123',
//         'role' => 2,
//     ];
//     return view('emails.user_registration', ['user' => $data]);
// });
