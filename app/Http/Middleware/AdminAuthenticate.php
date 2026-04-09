<?php
// app/Http/Middleware/AdminAuthenticate.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // تحقق من أن هذا الجهاز هو آخر من سجل دخول
        $adminId = session('admin_id');
        $currentSessionId = session()->getId();
        
        $dbSessionId = \Illuminate\Support\Facades\DB::table('admins')
            ->where('id', $adminId)
            ->value('last_session_id');

        if ($dbSessionId && $dbSessionId !== $currentSessionId) {
            session()->flush();
            return redirect()->route('admin.login')->with('error', 'تم تسجيل الدخول من جهاز آخر، يرجى إعادة تسجيل الدخول.');
        }

        return $next($request);
    }
}
