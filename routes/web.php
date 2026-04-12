<?php

use Illuminate\Support\Facades\Route;

// Index Page
Route::get('/', function () {
    return view('frontend.index');
});



Route::get('/admin/login', function () {
    return view('backend.admin-login');
});

Route::get('/admin/dashboard', function () {
    return view('backend.dashboard');
});

Route::get('/admin/dashboard/apartment/create', function () {
    return view('backend.create');
});

Route::get('/admin/dashboard/apartment/{id}/edit', function () {
    return view('backend.edit');
});


// Tenant All Page
// Tenant Registration Page
Route::get('/tenant/registration', function () {
    return view('frontend.register');
});

// Tenant Login Page
Route::get('/tenant/login', function () {
    return view('frontend.tenant-login');
});

// Tenant Dashboard
Route::get('/tenant/dashboard', function () {
    return view('frontend.dashboard');
});

Route::get('/admin/dashboard/tenant-list', function(){
    return view('backend.tenant-list') ;
});
