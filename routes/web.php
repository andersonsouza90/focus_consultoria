<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\USerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CadastrarController;
use App\Http\Controllers\UploadController;

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

// Route::get('/', function () {
//     //return "login";
//     return view('login');
// });

Route::get('/',[LoginController::class, 'login'])->name('login');
Route::get('/login',[LoginController::class, 'login']);
Route::get('/cadastrar',[CadastrarController::class, 'cadastrar'])->name('route.cadastrarView');
Route::post('/cadastrar-post',[CadastrarController::class, 'cadastrarPost'])->name('route.cadastrarPost');
Route::post('/autenticacao',[UserController::class, 'autenticacao'])->name('route.autenticacao');
Route::get('/logout',[LoginController::class, 'logout'])->name('route.logout');
Route::get('/home',[HomeController::class, 'home'])->name('route.home');


Route::get('/upload',[UploadController::class, 'upload'])->name('route.upload');
Route::get('/downloadExemplo',[UploadController::class, 'donwloadExemplo']);
//upload arquivo
Route::post('/importar', [UploadController::class, 'importar'])->name('route.importar');

