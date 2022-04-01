<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    // Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('videos', VideoController::class);
});

Route::get('show/{video}', function ($video) {
    $path = storage_path('app/videos/' . $video);
    if (File::exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->name('show-video-url');

Route::get('download/{video}', function ($video) {
    $path = storage_path('app/videos/' . $video);
    if (File::exists($path)) {
        $model = App\Models\Video::where('hashname', $video)->first();
        return response()->download($path, $model->filename);
    }
    abort(404);
})->name('download-video-url');
