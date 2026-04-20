<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::livewire('/register', 'pages::auth.register')->name('register');

Route::livewire('/courses', 'pages::courses.index')->name('courses.index');

Route::livewire('/courses/{course:slug}/lessons/{lesson:slug}', 'pages::courses.lessons')
    ->name('courses.lessons.show')
    ->middleware(['auth']);

Route::livewire('/courses/{course:slug}', 'pages::courses.show')->name('courses.show');

Route::get('/download-pdf/{id}', [CertificateController::class, 'downloadCertificatePDF'])->name('download.certificate');
