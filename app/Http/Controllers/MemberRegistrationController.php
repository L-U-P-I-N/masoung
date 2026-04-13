<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberRegistrationController extends Controller
{
    public function show(Request $request)
    {
        if ($request->hasCookie('member_registered')) {
            return redirect()->route('home')->with('info', 'لقد قمت بتسجيل بياناتك مسبقاً.');
        }
        return view('members.register');
    }

    public function store(Request $request)
    {
        // 1. Honeypot Check (Must be empty)
        if ($request->filled('website_verification_code')) {
            return back()->with('success', 'شكراً لك! سيتم مراجعة طلبك.'); // Fake success for bots
        }

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'country'    => 'required|string|max:100',
            'province'   => 'required|string|max:100',
            'city'       => 'required|string|max:100',
            'phone'      => 'nullable|array',
            'email'      => 'nullable|email|max:255',
            'bio'        => 'nullable|string',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 2. Duplicate Name Check
        $existsName = DB::table('members')->where('name', $data['name'])->exists();
        if ($existsName) {
            return back()->withErrors(['name' => 'هذا الاسم مسجل مسبقاً في قاعدة البيانات.'])->withInput();
        }

        // Phone processing and validation
        if (isset($data['phone']) && is_array($data['phone'])) {
            $formattedPhones = [];
            foreach ($data['phone'] as $number) {
                if (empty($number)) continue;
                $number = trim($number);
                
                if (str_starts_with($number, '+')) {
                    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
                    $fullNumber = $number;
                } elseif (str_starts_with($number, '00')) {
                    $cleanNumber = preg_replace('/[^0-9]/', '', substr($number, 2));
                    $fullNumber = '+' . $cleanNumber;
                } else {
                    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
                    $fullNumber = '+967 ' . $cleanNumber;
                }

                // Duplicate Phone Check (using clean number)
                $existsPhone = DB::table('members')->where('phone', 'LIKE', '%' . $cleanNumber . '%')->exists();
                if ($existsPhone) {
                    return back()->withErrors(['phone' => "رقم الهاتف ($number) مسجل مسبقاً لعضو آخر."])->withInput();
                }

                $formattedPhones[] = $fullNumber;
            }
            $data['phone'] = implode(', ', $formattedPhones);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 's3');
        }

        // Auto-approval logic
        $settings = DB::table('tribe_settings')->first();
        $isAutoApproved = false;
        if ($settings && $settings->auto_approve_start && $settings->auto_approve_end) {
            $now = now();
            if ($now->between($settings->auto_approve_start, $settings->auto_approve_end)) {
                $isAutoApproved = true;
            }
        }

        $data['is_active']  = $isAutoApproved ? 1 : 0;
        $data['sort_order'] = 0;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $memberId = DB::table('members')->insertGetId($data);
        $member = DB::table('members')->find($memberId);

        // Send Notification to Admin
        try {
            $adminEmail = $settings->contact_email ?? 'skjccm@gmail.com';
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\NewMemberNotification($member));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send member notification: ' . $e->getMessage());
        }

        $msg = $isAutoApproved 
            ? 'تم قبول عضويتك تلقائياً وتفعيلها بنجاح!' 
            : 'تم إرسال بياناتك بنجاح. سيتم مراجعتها من قبل الإدارة وتفعيلها قريباً.';

        return redirect()->route('home')
            ->with('success', $msg)
            ->cookie('member_registered', $data['name'], 525600); // 1 year cookie
    }
}
