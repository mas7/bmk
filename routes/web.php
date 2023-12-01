<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if ($request->user()?->isAdmin) {
        return redirect('/admin');
    }

    if ($request->user()?->isClient) {
        return redirect('/client');
    }

    if ($request->user()?->isContractor) {
        return redirect('/contractor');
    }

    return redirect('/client');
});
