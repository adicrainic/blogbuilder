<?php

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

Route::post('app/create_tag', [AdminController::class, 'addTag']);
Route::post('app/create_category', [AdminController::class, 'addCategory']);
Route::post('app/edit_tag', [AdminController::class, 'editTag']);
Route::post('app/edit_category', [AdminController::class, 'editCategory']);

Route::post('app/delete_tag', [AdminController::class, 'deleteTag']);
Route::post('app/upload', [AdminController::class, 'upload']);
Route::post('app/delete_image', [AdminController::class, 'delete_image']);
Route::post('app/delete_category', [AdminController::class, 'deleteCategory']);

Route::get('app/get_tags', [AdminController::class, 'get_tags']);
Route::get('app/get_category', [AdminController::class, 'get_category']);


Route::get('app/get_admins', [AdminController::class, 'get_admins']);
Route::post('app/create_admin', [AdminController::class, 'addAdmin']);
Route::post('app/edit_admin', [AdminController::class, 'editAdmin']);
Route::post('app/delete_admin', [AdminController::class, 'deleteAdmin']);

Route::post('app/admin_login', [AdminController::class, 'adminLogin']);


Route::get('/logout', [AdminController::class, 'logout']);



Route::get('/', [AdminController::class, 'index']);
Route::any('{slug}', [AdminController::class, 'index']);

