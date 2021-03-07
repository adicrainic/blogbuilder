<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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

Route::prefix('app')->middleware(AdminCheck::class)->group(function() {
    Route::post('/create_tag', [AdminController::class, 'addTag']);
    Route::post('/create_category', [AdminController::class, 'addCategory']);
    Route::post('/edit_tag', [AdminController::class, 'editTag']);
    Route::post('/edit_category', [AdminController::class, 'editCategory']);
    Route::post('/delete_tag', [AdminController::class, 'deleteTag']);
    Route::post('/upload', [AdminController::class, 'upload']);
    Route::post('/delete_image', [AdminController::class, 'delete_image']);
    Route::post('/delete_category', [AdminController::class, 'deleteCategory']);
    Route::get('/get_tags', [AdminController::class, 'get_tags']);
    Route::get('/get_category', [AdminController::class, 'get_category']);
    Route::get('/get_admins', [AdminController::class, 'get_admins']);
    Route::post('/create_admin', [AdminController::class, 'addAdmin']);
    Route::post('/edit_admin', [AdminController::class, 'editAdmin']);
    Route::post('/delete_admin', [AdminController::class, 'deleteAdmin']);
    Route::post('/admin_login', [AdminController::class, 'adminLogin']);
});


Route::get('/logout', [AdminController::class, 'logout']);
Route::get('/', [AdminController::class, 'index']);
Route::any('{slug}', [AdminController::class, 'index']);

