<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberRegistrationController;

// ===== الصفحات العامة (للزوار) =====
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/members', [PublicController::class, 'members'])->name('members');
Route::get('/members/{id}', [PublicController::class, 'memberShow'])->name('members.show');
Route::get('/activities', [PublicController::class, 'activities'])->name('activities');
Route::get('/activities/{id}', [PublicController::class, 'activityShow'])->name('activities.show');
Route::get('/news', [PublicController::class, 'news'])->name('news');
Route::get('/news/{id}', [PublicController::class, 'newsShow'])->name('news.show');

// ===== مسار الادمن السري (بدون زر في الواجهة) =====
Route::get('/admin-access', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin-access', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin-logout', [AuthController::class, 'logout'])->name('admin.logout');

// مسارات تسجيل الأعضاء (عامة)
Route::get('/register-member', [MemberRegistrationController::class, 'show'])->name('member.register');
Route::post('/register-member', [MemberRegistrationController::class, 'store'])->name('member.register.post')->middleware('throttle:registration');

Route::get('/run-migration', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    
    // تأكد من أن حسابك الأساسي هو المدير العام
    \Illuminate\Support\Facades\DB::table('admins')
        ->where('email', 'skjccm@gmail.com')
        ->update(['role' => 'super_admin', 'permissions' => json_encode(['manage_members', 'manage_activities', 'manage_news'])]);
        
    return "تم تحديث الترقية! حسابك الآن يمتلك صلاحيات المدير العام.";
});

// مسارات استعادة كلمة المرور
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('admin.password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('admin.password.email');
Route::get('/verify-code', [AuthController::class, 'showVerifyCodePage'])->name('admin.password.reset.verify');
Route::post('/verify-code', [AuthController::class, 'verifyResetCode'])->name('admin.password.verify.post');
Route::get('/new-password', [AuthController::class, 'showNewPasswordPage'])->name('admin.password.new');
Route::post('/new-password', [AuthController::class, 'updateNewPassword'])->name('admin.password.update.reset');

// ===== لوحة الادمن (محمية) =====
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // إدارة المستخدمين (للمدير العام فقط)
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'userDelete'])->name('users.delete');

    // سجل النشاطات
    Route::get('/logs', [AdminController::class, 'activityLogs'])->name('logs');
    Route::delete('/logs/clear', [AdminController::class, 'clearActivityLogs'])->name('logs.clear');

    // الأعضاء
    Route::get('/members', [AdminController::class, 'members'])->name('members');
    Route::get('/members/create', [AdminController::class, 'memberCreate'])->name('members.create');
    Route::post('/members', [AdminController::class, 'memberStore'])->name('members.store');
    Route::patch('/members/approve-all', [AdminController::class, 'memberApproveAll'])->name('members.approve.all');
    Route::patch('/members/approve-bulk', [AdminController::class, 'memberApproveBulk'])->name('members.approve.bulk');
    Route::get('/members/{id}/edit', [AdminController::class, 'memberEdit'])->name('members.edit');
    Route::put('/members/{id}', [AdminController::class, 'memberUpdate'])->name('members.update');
    Route::delete('/members/{id}', [AdminController::class, 'memberDelete'])->name('members.delete');
    Route::patch('/members/{id}/approve', [AdminController::class, 'memberApprove'])->name('members.approve');
    Route::delete('/members/{id}/reject', [AdminController::class, 'memberReject'])->name('members.reject');

    // الأنشطة
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities');
    Route::get('/activities/create', [AdminController::class, 'activityCreate'])->name('activities.create');
    Route::post('/activities', [AdminController::class, 'activityStore'])->name('activities.store');
    Route::get('/activities/{id}/edit', [AdminController::class, 'activityEdit'])->name('activities.edit');
    Route::put('/activities/{id}', [AdminController::class, 'activityUpdate'])->name('activities.update');
    Route::delete('/activities/{id}', [AdminController::class, 'activityDelete'])->name('activities.delete');

    // الأخبار
    Route::get('/news', [AdminController::class, 'news'])->name('news');
    Route::get('/news/create', [AdminController::class, 'newsCreate'])->name('news.create');
    Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminController::class, 'newsEdit'])->name('news.edit');
    Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [AdminController::class, 'newsDelete'])->name('news.delete');

    // الإعدادات
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    Route::get('/change-password', [AdminController::class, 'passwordEdit'])->name('password.edit');
    Route::put('/change-password', [AdminController::class, 'passwordUpdate'])->name('password.update');
});
