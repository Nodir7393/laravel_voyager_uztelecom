<?php

use App\Events\Notify;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TypeOfPurchase;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Site\ApplicationController;
use App\Http\Controllers\Site\DashboardController;
use App\Http\Controllers\Site\ProfileController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Site\FaqsController;
use App\Http\Controllers\WarehouseController;
use App\View\Components\laravelYajra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BranchController;
USE App\Http\Controllers\HomeController;
use App\Http\Controllers\EimzoController;

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

Route::get('/auth/user', function (){
    return response()->json(['serialNumber' => auth()->user()->pinfl]);
});
Route::post('eimzo/login', [EimzoController::class, 'auth'])->name('eri.login');
Route::post('eimzo/change/key', [EimzoController::class, 'change_key'])->name('eri.change_key');
Route::post('eimzo/register', [EimzoController::class, 'register_post'])->name('eri.register');
Route::group([
    'middleware' => 'web',
    'prefix' => 'eimzo',
    'as' => 'eimzo.',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::get('login', [EimzoController::class,'login'])->name('showLogin');
    Route::post('postLogin', [EimzoController::class,'auth'])->name('postLogin');
});
Route::get('branches/{id}/getData', [BranchController::class,'getData'])->name('signers.getData');
Route::get('/branches/ajax_branch', [BranchController::class,'ajax_branch'])->name('branches.ajax_branch');

    Route::controller(ReportController::class)->group(function() {
        Route::post('/request','request')->name('request');
        Route::any('/report/request/{id}','report')->name('report');
        Route::any('/report/export/{id}','report_export')->name('report_export');

    });
Route::get('roles/getData',[RoleController::class,'getData'])->name('voyager.roles.getData');
Route::get('/export-users',[UserController::class,'exportUsers'])->name('export-users');
Route::get('departments/getData',[DepartmentController::class,'getData'])->name('voyager.departments.getData');
Route::post('/warehouse',[WarehouseController::class,'create'])->name('warehouse.create');
Route::post('/delete_file/{application}/{column}',[ApplicationController::class,'file_delete'])->name('delete_file');

Route::get('/user/{user}/leader',[UserController::class,'changeLeader'])->name('users.leader');

Route::get('/user/{user}/status',[UserController::class,'changeStatus'])->name('users.status');

Route::get('/', function () {
    return redirect()->route('site.applications.index');
});
Route::post('/uploadimage/{application}/update', [ApplicationController::class, 'uploadImage'])->name('uploadImage');
Route::group([
    'prefix' => 'admin',
    'middleware' => 'isAdmin'
], function () {
    Voyager::routes();
    Route::put('roles/{id}/update',[RoleController::class,'update'])->name('voyager.roles.update');
    Route::get('roles/{id}/delete',[RoleController::class,'delete'])->name('voyager.roles.delete');

    Route::delete('/users/{user}/destroy',[UserController::class,'delete'])->name('voyager.users.destroy');

    Route::get('/users/{id}/restore',[UserController::class,'restore'])->name('voyager.users.restore');

    Route::post('create/role',[RoleController::class,'store'])->name('voyager.roles.store');
    Route::put('users/{id}/update',[UserController::class,'update'])->name('voyager.users.update');
    Route::get('roles/',[RoleController::class,'index'])->name('voyager.roles.index');
    Route::get('departments/',[DepartmentController::class,'index'])->name('voyager.departments.index');
    Route::get('type-of-purchase/{id}/edit',[TypeOfPurchase::class,'edit'])->name('voyager.type-of-purchase.edit');
    Route::post('type-of-purchase/update',[TypeOfPurchase::class,'update'])->name('type-of-purchase.update');
    Route::get('branches/{id}/signers',[BranchController::class,'edit'])->name('signers.add');

});
Auth::routes();

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth' ]
], function()
{
    Route::controller(BranchController::class)->group(function () {
        Route::post('/branches/{id}/post', 'update')->name('signers.update');
        Route::post('/branches/putCache', 'putCache')->name('branches.putCache');
        Route::get('/branches/view', 'view')->name('branches.view');
    });
    Route::group(
        [
            'as' => 'site.',
            'prefix' => 'site',
            'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]

        ],

        function(){
            Route::group(
                [
                    'as' => 'profile.',
                    'prefix' => 'profile',
                ],
                function(){
                    Route::get('', [ProfileController::class, 'index'])->name('index');
                    Route::get('/change/key', [ProfileController::class, 'change_key'])->name('change_key');
                    Route::get('{id}/show', [ProfileController::class, 'other'])->name('other');
                    Route::put('update', [ProfileController::class, 'update'])->name('update');
                });
            Route::group(
                [
                    'as' => 'report.',
                    'prefix' => 'report',
                ],
                function(){
                    Route::get('/{id}',[ReportController::class,'index'])->name('index');
                });
            Route::group(
                [
                    'as' => 'applications.',
                    'prefix' => 'applications',
                ],
                function(){
                    Route::group(
                        [
                            'as' => 'drafts.',
                            'prefix' => 'drafts',
                        ],
                        function (){
                            Route::get('', [ApplicationController::class, 'show_draft'])->name('index');
                            Route::get('show_draft_getData', [ApplicationController::class, 'show_draft_getData'])->name('show_draft_getData');
                        });
                    Route::controller(ApplicationController::class)->group(function() {
                        Route::get('', 'index')->name('index');
                        Route::get('index_getData', 'index_getData')->name('index_getData');
                        Route::get('my_applications', 'my_applications')->name('my_applications');
                        Route::get('my_applications_getData', 'my_applications_getData')->name('my_applications_getData');
                        Route::get('{status}/show_status','show_status')->name('show_status');
                        Route::get('status_table/show','status_table')->name('status_table');
                        Route::get('to_sign/','to_sign')->name('to_sign');
                        Route::get('to_sign/show','to_sign_data')->name('to_sign_data');
                        Route::get('performer_status','performer_status_get')->name('performer_status_get');
                        Route::post('performer_status/post','performer_status_post')->name('performer_status_post')->middleware('branch');
                        Route::get('performer_status/show','performer_status')->name('performer_status');
                        Route::get('list','getdata')->name('list');
                        Route::get('list/signedocs/{application}','SignedDocs')->name('list.signedocs');
                        Route::get('list/{signedocs_id}/{application_id}/delete','SignedDocsDelete')->name('delete.signedocs');
                        Route::get('{application}/show/{view?}','show')->name('show')->middleware('branch');
                        Route::get('{application}/edit','edit')->name('edit')->middleware('branch');
                        Route::get('{application}/clone','clone')->name('clone')->middleware('branch');
                        Route::post('{application}/update','update')->name('update')->middleware('branch');
                        Route::post('{application}/edit_update','edit_update')->name('edit_update')->middleware('branch');
                        Route::get('{application}/destroy','destroy')->name('destroy')->middleware('branch','application_user_id');
                        Route::get('create','create')->name('create')->middleware('branch');
                        Route::post('store','store')->name('store')->middleware('branch');
                        Route::put('{application}/vote','vote')->name('vote')->middleware('branch');
                        Route::post('{application}/is_more_than_limit','is_more_than_limit')->name('is_more_than_limit')->middleware('branch','application_user_id');
                        Route::get('getAll','getAll')->name('getAll');
                        Route::get('daterangepicker','daterangepicker')->name('daterangepicker');
                    });
                });

            Route::group(
                [
                    'as' => 'faqs.',
                    'prefix' => 'faqs',
                ],
                function(){
                    Route::controller(FaqsController::class)->group(function() {
                        Route::get('','index')->name('index');
                        Route::get('{faq}/show','show')->name('show');
                    });
                    Route::get('{faq}/edit', [ApplicationController::class, 'edit'])->name('edit');
                    Route::post('{faq}/update', [ApplicationController::class, 'update'])->name('update');
                    Route::get('create', [ApplicationController::class, 'create'])->name('create');
                    Route::post('{application}/store', [ApplicationController::class, 'store'])->name('store');
                    Route::post('form', [ApplicationController::class, 'form'])->name('form');
                    Route::get('getAll', [ApplicationController::class, 'getAll'])->name('getAll');
                });
            Route::group(
                [
                    'as' => 'dashboard.',
                    'prefix' => 'dashboard'
                ],
                function(){
                    Route::get('index', [DashboardController::class, 'index'])->name('index');
                });
        }
    );

});
Route::get('/home', [HomeController::class, 'index']);
Route::get('/test/{id}', [App\Http\Controllers\Controller::class, 'test']);

Route::get('getRoles', [ApplicationController::class, '']);
Route::get('sign/index', function () {
    return redirect()->route('site.applications.index');
})->name('sign.index');
Route::get('redirect', function (){
    return redirect()->route('site.applications.index');
})->name('eimzo.auth.back');
Route::get('eimzo/back',  function(){
    return redirect()->back();
})->name('eimzo.back');
Route::get('eimzo/login', [EimzoController::class, 'login'])->name('eimzo.login.index');
