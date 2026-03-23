<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/news', [App\Http\Controllers\Member\PostController::class, 'index']);
Route::get('/categorynews', [App\Http\Controllers\Member\CategoryNewsController::class, 'index']);
Route::get('/listcategory', [App\Http\Controllers\Member\CategoryNewsController::class, 'listCategoryDisplayTrue']);
Route::get('/searchnews', [App\Http\Controllers\Member\PostController::class, 'searchNews']);
Route::get('/news/top-interactive', [App\Http\Controllers\Member\PostController::class, 'TopInteractiveNewsPerCategory']);
Route::get('/news/{friendly_url}', [App\Http\Controllers\Member\PostController::class, 'show']);


//---------------------------------------Auth Member
Route::post('/member/login', [App\Http\Controllers\Member\MemberController::class, 'login']);
Route::post('/member/forgot-password', [App\Http\Controllers\Member\MemberController::class, 'forgotpassword']);
Route::post('/member/reset-password', [App\Http\Controllers\Member\MemberController::class, 'resetpassword']);
Route::post('/member/register', [App\Http\Controllers\Member\MemberController::class, 'register']);

//--------------------------------------- Member ---------------------------------------
Route::middleware('auth:member')->prefix('member')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Member\MemberController::class, 'logout']);
    Route::post('/update-profile', [App\Http\Controllers\Member\MemberController::class, 'updateProfile']);
    Route::get('/notifications/unread-count', [App\Http\Controllers\Member\NotificationContronler::class, 'getUnreadCount']);
    Route::get('/notifications', [App\Http\Controllers\Member\NotificationContronler::class, 'index']);
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\Member\NotificationContronler::class, 'markAsRead']);
    Route::resource('comment', App\Http\Controllers\Member\CommentController::class);
});

//--------------------------------------- Admin ---------------------------------------
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'login']);

Route::middleware(['auth:admin', CheckAdmin::class])->prefix('admin')->group(function () {
    //--------------------Authen
    Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout']);
    //--------------------Member
    Route::resource('member', App\Http\Controllers\Admin\MemberController::class);
    Route::delete('member-delete', [App\Http\Controllers\Admin\MemberController::class, 'destroy']);
    //--------------------Post
    Route::resource('news', App\Http\Controllers\Admin\PostController::class);
    Route::delete('news-delete', [App\Http\Controllers\Admin\PostController::class, 'destroyMany']);
    //--------------------Category Post
    Route::resource('categorynews', App\Http\Controllers\Admin\CategoryNewsController::class);
    //---------------------Manage Member
    Route::get('member-activities', [App\Http\Controllers\Admin\ManageMemberController::class, 'index']);
    //---------------------DashBoard
    Route::get('/dashboard/member-statistics', [App\Http\Controllers\Admin\DashBoardController::class, 'memberStatistics']);
    Route::get('/dashboard/news-views', [App\Http\Controllers\Admin\DashBoardController::class, 'extremeViews']);
    //----------------------Banner
    Route::resource('banner', App\Http\Controllers\Admin\BannerController::class);
    //----------------------Footer
    Route::resource('footer', App\Http\Controllers\Admin\FooterController::class);
});
