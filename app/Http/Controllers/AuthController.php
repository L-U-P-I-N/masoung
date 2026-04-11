<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'email.email'       => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min'      => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        $admin = DB::table('admins')->where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $sessionId = session()->getId();
            
            DB::table('admins')->where('id', $admin->id)->update([
                'last_session_id' => $sessionId
            ]);

            session([
                'admin_logged_in' => true,
                'admin_id'        => $admin->id,
                'admin_name'      => $admin->name,
                'admin_email'     => $admin->email,
                'last_session_id' => $sessionId,
            ]);

            // إرسال تنبيه بالبريد الإلكتروني مباشرة (بدون queue)
            try {
                $settings = DB::table('tribe_settings')->first();
                $notifyEmail = $settings->contact_email ?? 'skjccm@gmail.com';
                $alertData = [
                    'tribe_name'  => $settings->tribe_name ?? 'القبيلة',
                    'admin_name'  => $admin->name,
                    'admin_email' => $admin->email,
                    'ip_address'  => $request->ip(),
                    'timestamp'   => now()->format('Y-m-d H:i:s'),
                ];
                Mail::send('emails.login_alert', $alertData, function ($message) use ($notifyEmail) {
                    $message->to($notifyEmail)
                            ->subject('تنبيه أمان: تسجيل دخول جديد للوحة الإدارة');
                });
            } catch (\Exception $e) {
                Log::error('Login alert email failed: ' . $e->getMessage());
            }

            return redirect()->route('admin.dashboard')->with('success', 'مرحباً بك ' . $admin->name);
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'])->withInput();
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    // ===== استعادة كلمة المرور =====
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $admin = DB::table('admins')->where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['email' => 'هذا البريد الإلكتروني غير مسجل لدينا']);
        }

        // إنشاء رمز عشوائي من 6 أرقام
        $code = rand(100000, 999999);
        
        // حفظ الرمز في قاعدة البيانات
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $request->email],
            ['code' => $code, 'created_at' => now()]
        );

        // إرسال رمز التحقق مباشرة عبر SMTP (بدون queue حتى يصل فوراً)
        try {
            $recipientEmail = $request->email;
            Mail::mailer('resend')->send('emails.reset_code', ['code' => $code], function ($message) use ($recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('رمز إعادة تعيين كلمة المرور - قبيلة مسونق');
            });
        } catch (\Exception $e) {
            Log::error('Reset code email failed: ' . $e->getMessage());
            // إظهار الخطأ الحقيقي للمساعدة في الـ Debugging (مؤقتاً)
            $errorMessage = 'فشل إرسال الرمز: ' . $e->getMessage();
            return back()->withInput()->withErrors(['email' => $errorMessage]);
        }

        return redirect()->route('admin.password.reset.verify', ['email' => $request->email])
                       ->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني');
    }

    public function showVerifyCodePage(Request $request)
    {
        $email = $request->email;
        if (!$email) {
            return redirect()->route('admin.password.request')->with('error', 'يرجى إدخال البريد الإلكتروني أولاً');
        }
        return view('auth.verify_code', compact('email'));
    }

    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string',
        ]);

        $record = DB::table('password_reset_codes')
                    ->where('email', $request->email)
                    ->where('code', $request->code)
                    ->where('created_at', '>', now()->subMinutes(15))
                    ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'الرمز غير صحيح أو انتهت صلاحيته']);
        }

        // حفظ البريد والرمز في الجلسة للانتقال للمرحلة التالية
        session(['reset_email' => $request->email, 'reset_code' => $request->code]);

        return redirect()->route('admin.password.new');
    }

    public function showNewPasswordPage()
    {
        if (!session('reset_email') || !session('reset_code')) {
            return redirect()->route('admin.password.request')->with('error', 'يرجى البدء من البداية');
        }
        return view('auth.new_password');
    }

    public function updateNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $email = session('reset_email');
        $code = session('reset_code');

        // تحقق أخير للتأكد من أن الرمز لا يزال صالحاً
        $record = DB::table('password_reset_codes')
                    ->where('email', $email)
                    ->where('code', $code)
                    ->first();

        if (!$record) {
            return redirect()->route('admin.password.request')->with('error', 'حدث خطأ، يرجى المحاولة مرة أخرى');
        }

        // تحديث كلمة المرور
        DB::table('admins')->where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // حذف الرمز والجلسة
        DB::table('password_reset_codes')->where('email', $email)->delete();
        session()->forget(['reset_email', 'reset_code']);

        return redirect()->route('admin.login')->with('success', 'تم تغيير كلمة المرور بنجاح، يمكنك الآن تسجيل الدخول');
    }
}
