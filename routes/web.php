<?php

use App\Http\Controllers\SendEmail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('admin/contact', [SendEmail::class, 'contactMail'])->name('contact');
