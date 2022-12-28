<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//get

Route::apiResource('/signer', App\Http\Controllers\Api\SignerController::class);
Route::apiResource('/env', App\Http\Controllers\Api\EnvController::class);
Route::apiResource('/divisi', App\Http\Controllers\Api\DevisiController::class);
Route::get('/direksi', [\App\Http\Controllers\Api\SignerController::class, 'getDireksi']);
Route::get('/manager', [\App\Http\Controllers\Api\SignerController::class, 'getManager']);

// guess
Route::apiResource('/login', App\Http\Controllers\Api\AuthController::class);

// all auth
Route::apiResource('/detail', App\Http\Controllers\Api\AuthController::class)->middleware(['auth:sanctum']);;
Route::get('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware(['auth:sanctum']);
Route::post('/update/status', [App\Http\Controllers\Api\SuratController::class, 'updateStatus'])->middleware(['auth:sanctum']);
Route::post('/revisi', [\App\Http\Controllers\Api\HistoryController::class, 'revisi' ])->middleware(['auth:sanctum']);
Route::get('/revisi/{id_surat}', [\App\Http\Controllers\Api\HistoryController::class, 'getHistory' ])->middleware(['auth:sanctum']);
Route::get('/signed', [\App\Http\Controllers\Api\ViewSuratController::class, 'getSigned' ])->middleware(['auth:sanctum']);
Route::get('/signed/{id_divisi}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getSignedByDivisi' ])->middleware(['auth:sanctum']);
Route::get('/signed/cari/{request}', [\App\Http\Controllers\Api\ViewSuratController::class, 'cari' ])->middleware(['auth:sanctum']);
Route::get('/surat-masuk', [\App\Http\Controllers\Api\SuratMasukController::class, 'index' ])->middleware(['auth:sanctum']);
Route::get('/surat-masuk/cari/{request}', [\App\Http\Controllers\Api\SuratMasukController::class, 'show' ])->middleware(['auth:sanctum']);
Route::post('/kirim-email',[App\Http\Controllers\Api\MailController::class,'index'])->middleware(['auth:sanctum']);
Route::post('/add-riwayat',[App\Http\Controllers\Api\RiwayatSuratMasukController::class,'store'])->middleware(['auth:sanctum']);
Route::get('/surat_masuk/{id_direksi}', [\App\Http\Controllers\Api\SuratMasukController::class, 'getDireksi'])->middleware(['auth:sanctum']);
Route::post('/surat_masuk/disposisi', [\App\Http\Controllers\Api\SuratMasukController::class, 'disposisi'])->middleware(['auth:sanctum']);
Route::post('/change-pass', [\App\Http\Controllers\Api\AuthController::class, 'changePass'])->middleware(['auth:sanctum']);
Route::get('/direktorat/{id_direktorat}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getSuratByDirektorat'])->middleware(['auth:sanctum']);



// admin
Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function(){
    Route::apiResource('admin/posts', App\Http\Controllers\Api\PostController::class);
    Route::apiResource('admin/surat', App\Http\Controllers\Api\SuratController::class);
    Route::get('admin/lastid', [App\Http\Controllers\Api\SuratController::class, 'getLast']);
    Route::get('admin/submitted/{id_user}', [App\Http\Controllers\Api\SuratController::class, 'getSubmitted']);
    Route::post('admin/update', [\App\Http\Controllers\Api\SuratController::class, 'edit']);
    Route::get('admin/mail/manager/{id_divisi}', [App\Http\Controllers\Api\MailController::class, 'getMailManager']);
});

// administrator

    Route::apiResource('administrator/suratmasuk', \App\Http\Controllers\Api\SuratMasukController::class)->middleware(['auth:sanctum']);


// manager
Route::middleware(['auth:sanctum', 'abilities:manager'])->group(function(){
    Route::get('manager/mustreview/{id_divisi}', [\App\Http\Controllers\Api\SuratController::class, 'getReview']);
    Route::get('manager/detail/{id_surat}', [App\Http\Controllers\Api\SuratController::class, 'getSuratDetail']);
    Route::get('manager/approve/{id_divisi}', [App\Http\Controllers\Api\SuratController::class, 'approveByManager']);
    Route::get('manager/must_sign/{id_manager}', [App\Http\Controllers\Api\ViewSuratController::class, 'getMustSignbyManager']);
    Route::post('manager/sign', [\App\Http\Controllers\Api\SuratController::class, 'sign']);  
    Route::get('manager/surat_masuk/{id_manager}', [\App\Http\Controllers\Api\SuratMasukController::class, 'getManager']);
});

// direksi
Route::middleware(['auth:sanctum', 'abilities:direksi'])->group(function(){
    Route::get('direksi/surat/{id_direksi}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getReviewDireksi']);
    Route::get('direksi/detail/{id_surat}', [\App\Http\Controllers\Api\SuratController::class, 'getSuratDetail']);  
    Route::get('direksi/surat/other/{id_direksi}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getOtherDirektorat']);  
    Route::get('direksi/surat/mustsign/{id_direksi}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getMustSignbyDireksi']);  
    Route::post('direksi/surat/sign', [\App\Http\Controllers\Api\SuratController::class, 'sign']); 
   });

// dirut

Route::middleware(['auth:sanctum', 'abilities:dirut'])->group(function(){
    Route::get('dirut/mustsign/{id_dirut}', [\App\Http\Controllers\Api\ViewSuratController::class, 'getMustSignbyDirut']); 
    Route::get('dirut/detail/{id_surat}', [\App\Http\Controllers\Api\SuratController::class, 'getSuratDetail']);  
    Route::post('dirut/sign', [\App\Http\Controllers\Api\SuratController::class, 'sign']);  
   
});



Route::get('surat/detail/{id_surat}', [App\Http\Controllers\Api\SuratController::class, 'getSuratDetail']);
