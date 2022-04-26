<?php

use App\Http\Controllers\BackEnd\NoteController;
use App\Http\Controllers\BackEnd\ShortController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    //Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Note Controller

    //display Delete Notes
    Route::get('notes/softDeletes', [NoteController::class, 'trashed'])->name('notes.softDeletes');
    //Restore note
    Route::get('notes/restore/{id}', [NoteController::class, 'restore'])->name('notes.restore');
    //ForceDelete Note
    Route::get('notes/forceDelete/{id}', [NoteController::class, 'forceDelete'])->name('notes.forceDelete');
    //Report Note
    Route::get('notes/report', [NoteController::class, 'report'])->name('notes.report');

    Route::resource('notes', NoteController::class);
});


//Show Note from public link
Route::get('notes/share/{id}', [ShortController::class, 'share'])->name('notes.share');


require __DIR__ . '/auth.php';
