<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberRegistrationController extends Controller
{
    public function show()
    {
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

        // Country config for validation and prefixing
        $countryMap = [
            'اليمن'    => ['code' => '+967', 'len' => 9],
            'العراق'    => ['code' => '+964', 'len' => 10],
            'السعودية' => ['code' => '+966', 'len' => 9],
            'الإمارات' => ['code' => '+971', 'len' => 9],
            'عمان'     => ['code' => '+968', 'len' => 8],
            'الكويت'   => ['code' => '+965', 'len' => 8],
            'قطر'      => ['code' => '+974', 'len' => 8],
            'البحرين'  => ['code' => '+973', 'len' => 8],
            'مصر'      => ['code' => '+20',   'len' => 10],
            'الأردن'    => ['code' => '+962', 'len' => 9],
        ];

        $config = $countryMap[$data['country']] ?? ['code' => '+', 'len' => '7,15'];

        // Unify location for storage
        $data['location'] = "{$data['country']} - {$data['province']} - {$data['city']}";

        // Phone processing and validation
        if (isset($data['phone']) && is_array($data['phone'])) {
            $formattedPhones = [];
            foreach ($data['phone'] as $number) {
                if (empty($number)) continue;
                $cleanNumber = preg_replace('/[^0-9]/', '', $number);
                
                // Flexible vs Strict length validation
                if (str_contains($config['len'], ',')) {
                    $range = explode(',', $config['len']);
                    if (strlen($cleanNumber) < $range[0] || strlen($cleanNumber) > $range[1]) {
                        return back()->withErrors(['phone' => "رقم الهاتف غير صحيح (يجب أن يكون بين {$range[0]} و {$range[1]} أرقام)."])->withInput();
                    }
                } else {
                    if (strlen($cleanNumber) != $config['len']) {
                        return back()->withErrors(['phone' => "رقم الهاتف في {$data['country']} يجب أن يكون {$config['len']} أرقام."])->withInput();
                    }
                }

                // 3. Duplicate Phone Check
                $existsPhone = DB::table('members')->where('phone', 'LIKE', '%' . $cleanNumber . '%')->exists();
                if ($existsPhone) {
                    return back()->withErrors(['phone' => "رقم الهاتف ($number) مسجل مسبقاً لعضو آخر."])->withInput();
                }

                $formattedPhones[] = ($config['code'] === '+' ? '+' : $config['code']) . ' ' . $cleanNumber;
            }
            $data['phone'] = implode(', ', $formattedPhones);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 'public');
        }

        // Members registered through the public form are INACTIVE by default (Pending Approval)
        $data['is_active']  = 0;
        $data['sort_order'] = 0;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('members')->insert($data);

        return redirect()->route('home')->with('success', 'تم إرسال بياناتك بنجاح. سيتم مراجعتها من قبل الإدارة وتفعيلها قريباً.');
    }
}
