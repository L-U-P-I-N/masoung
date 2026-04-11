<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    private function logActivity($action, $modelType, $modelId = null, $details = null)
    {
        $admin = clone DB::table('admins')->where('id', session('admin_id'))->first();
        if (!$admin) return;

        DB::table('admin_activity_logs')->insert([
            'admin_id'   => $admin->id,
            'admin_name' => $admin->name,
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'details'    => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    private function hasPermission($perm)
    {
        $admin = DB::table('admins')->where('id', session('admin_id'))->first();
        if (!$admin) return false;
        if ($admin->role === 'super_admin') return true;
        
        $perms = json_decode($admin->permissions, true) ?: [];
        return in_array($perm, $perms);
    }

    // ===== لوحة التحكم =====
    public function dashboard()
    {
        $stats = [
            'members'         => DB::table('members')->where('is_active', 1)->count(),
            'pending_members' => DB::table('members')->where('is_active', 0)->count(),
            'news'            => DB::table('news')->count(),
            'activities'      => DB::table('activities')->count(),
        ];
        $latestNews = DB::table('news')->orderBy('created_at', 'desc')->limit(4)->get();
        $latestActs = DB::table('activities')->orderBy('created_at', 'desc')->limit(4)->get();

        return view('admin.dashboard', compact('stats', 'latestNews', 'latestActs'));
    }

    // ===== الأعضاء =====
    public function members()
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $members = DB::table('members')->orderBy('sort_order')->get();
        return view('admin.members.index', compact('members'));
    }

    public function memberCreate()
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        return view('admin.members.form', ['member' => null]);
    }

    public function memberStore(Request $request)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
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
            'sort_order' => 'nullable|integer',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Phone processing
        if (isset($data['phone']) && is_array($data['phone'])) {
            $formattedPhones = [];
            foreach ($data['phone'] as $number) {
                if (empty($number)) continue;
                $number = trim($number);
                
                if (str_starts_with($number, '+')) {
                    $formattedPhones[] = $number;
                } elseif (str_starts_with($number, '00')) {
                    $formattedPhones[] = '+' . substr($number, 2);
                } else {
                    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
                    $formattedPhones[] = '+967 ' . $cleanNumber;
                }
            }
            $data['phone'] = implode(', ', $formattedPhones);
        }

        if (empty($data['position'])) $data['position'] = 'member';

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('members', 's3');
        }

        $data['is_active']  = $request->has('is_active') ? 1 : 0;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $memberId = DB::table('members')->insertGetId($data);
        $this->logActivity('created', 'member', $memberId, ['name' => $data['name']]);
        return redirect()->route('admin.members')->with('success', 'Member added successfully');
    }

    public function memberEdit($id)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $member = DB::table('members')->find($id);
        return view('admin.members.form', compact('member'));
    }

    public function memberUpdate(Request $request, $id)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
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
            'sort_order' => 'nullable|integer',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Phone processing
        if (isset($data['phone']) && is_array($data['phone'])) {
            $formattedPhones = [];
            foreach ($data['phone'] as $number) {
                if (empty($number)) continue;
                $number = trim($number);
                if (str_starts_with($number, '+')) {
                    $formattedPhones[] = $number;
                } elseif (str_starts_with($number, '00')) {
                    $formattedPhones[] = '+' . substr($number, 2);
                } else {
                    $cleanNumber = preg_replace('/[^0-9]/', '', $number);
                    $formattedPhones[] = '+967 ' . $cleanNumber;
                }
            }
            $data['phone'] = implode(', ', $formattedPhones);
        }

        if ($request->hasFile('photo')) {
            $old = DB::table('members')->where('id', $id)->value('photo');
            if ($old) { try { Storage::disk('s3')->delete($old); } catch (\Exception $e) {} }
            $data['photo'] = $request->file('photo')->store('members', 's3');
        }

        $data['is_active']  = $request->has('is_active') ? 1 : 0;
        $data['updated_at'] = now();

        DB::table('members')->where('id', $id)->update($data);
        $this->logActivity('updated', 'member', $id, ['name' => $data['name']]);
        return redirect()->route('admin.members')->with('success', 'Member updated successfully');
    }

    public function memberDelete($id)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $m = DB::table('members')->find($id);
        if ($m && $m->photo) {
            try { Storage::disk('s3')->delete($m->photo); } catch (\Exception $e) {}
        }
        DB::table('members')->where('id', $id)->delete();
        $this->logActivity('deleted', 'member', $id, ['name' => $m->name ?? 'Unknown']);
        return redirect()->route('admin.members')->with('success', 'Member deleted successfully');
    }

    public function memberApprove($id)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $m = DB::table('members')->find($id);
        DB::table('members')->where('id', $id)->update(['is_active' => 1, 'updated_at' => now()]);
        $this->logActivity('approved', 'member', $id, ['name' => $m->name ?? '']);
        return back()->with('success', 'تم قبول طلب العضوية بنجاح');
    }

    public function memberReject($id)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $m = DB::table('members')->find($id);
        if (!$m) return back()->with('error', 'العضو غير موجود');
        // حماية: لا يمكن رفض عضو مفعّل بالفعل
        if ($m->is_active) {
            return back()->with('error', 'لا يمكن رفض عضو تمت الموافقة عليه مسبقاً');
        }
        // حذف الصورة من S3 إن وجدت
        if ($m->photo) {
            try { Storage::disk('s3')->delete($m->photo); } catch (\Exception $e) {}
        }
        DB::table('members')->where('id', $id)->delete();
        $this->logActivity('rejected', 'member', $id, ['name' => $m->name ?? '']);
        return back()->with('success', 'تم رفض طلب العضوية وحذف البيانات');
    }

    public function memberApproveBulk(Request $request)
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $ids = $request->input('member_ids');
        if (!empty($ids) && is_array($ids)) {
            DB::table('members')->whereIn('id', $ids)->update(['is_active' => 1]);
            $this->logActivity('approved', 'member', null, ['title' => 'تمت الموافقة على مجموعة أعضاء ('.count($ids).')']);
            return back()->with('success', 'تمت الموافقة على الأعضاء المحددين بنجاح');
        }
        return back()->withErrors(['لم يتم تحديد أي عضو للموافقة عليه']);
    }

    public function memberApproveAll()
    {
        if (!$this->hasPermission('manage_members')) abort(403);
        $count = DB::table('members')->where('is_active', 0)->count();
        if ($count > 0) {
            DB::table('members')->where('is_active', 0)->update(['is_active' => 1]);
            $this->logActivity('approved', 'member', null, ['title' => 'تمت الموافقة على جميع الأعضاء المنتظرين ('.$count.')']);
            return back()->with('success', 'تمت الموافقة على جميع الأعضاء المنتظرين بنجاح');
        }
        return back()->with('success', 'لا يوجد أعضاء بانتظار الموافقة');
    }

    // ===== الأنشطة =====
    public function activities()
    {
        $activities = DB::table('activities')->orderBy('activity_date', 'desc')->get();
        return view('admin.activities.index', compact('activities'));
    }

    public function activityCreate()
    {
        if (!$this->hasPermission('manage_activities')) abort(403);
        return view('admin.activities.form', ['activity' => null]);
    }

    public function activityStore(Request $request)
    {
        if (!$this->hasPermission('manage_activities')) abort(403);
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'content'       => 'nullable|string',
            'activity_date' => 'required|date',
            'location'      => 'nullable|string|max:255',
            'images'        => 'nullable|array',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        // Remove images from data array (handled separately below)
        unset($data['images']);

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('activities', 's3');
            }
        }

        // Set image field
        $data['image'] = !empty($imagePaths) ? implode(',', $imagePaths) : null;

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['created_at']   = now();
        $data['updated_at']   = now();

        $actId = DB::table('activities')->insertGetId($data);
        $this->logActivity('created', 'activity', $actId, ['title' => $data['title']]);
        return redirect()->route('admin.activities')->with('success', 'تم إضافة النشاط بنجاح');
    }

    public function activityEdit($id)
    {
        if (!$this->hasPermission('manage_activities')) abort(403);
        $activity = DB::table('activities')->find($id);
        return view('admin.activities.form', compact('activity'));
    }

    public function activityUpdate(Request $request, $id)
    {
        if (!$this->hasPermission('manage_activities')) abort(403);
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'content'         => 'nullable|string',
            'activity_date'   => 'required|date',
            'location'        => 'nullable|string|max:255',
            'images'          => 'nullable|array',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            'existing_images' => 'nullable|array',
        ]);

        // Get existing images to keep & remove from data before DB update
        $existingImages = $request->input('existing_images', []);
        unset($data['images'], $data['existing_images']);

        // Handle new images
        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $newImagePaths[] = $image->store('activities', 's3');
            }
        }

        // Combine existing and new images
        $allImages = array_merge($existingImages, $newImagePaths);
        $data['image'] = !empty($allImages) ? implode(',', $allImages) : null;

        // Delete old images that were removed
        $oldActivity = DB::table('activities')->find($id);
        if ($oldActivity && $oldActivity->image) {
            $oldImages = explode(',', $oldActivity->image);
            foreach ($oldImages as $oldImage) {
                if (!in_array(trim($oldImage), $existingImages)) {
                    try { Storage::disk('s3')->delete(trim($oldImage)); } catch (\Exception $e) {}
                }
            }
        }

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['updated_at']   = now();

        DB::table('activities')->where('id', $id)->update($data);
        $this->logActivity('updated', 'activity', $id, ['title' => $data['title']]);
        return redirect()->route('admin.activities')->with('success', 'تم تعديل النشاط بنجاح');
    }

    public function activityDelete($id)
    {
        if (!$this->hasPermission('manage_activities')) abort(403);
        $act = DB::table('activities')->find($id);
        if ($act && $act->image) {
            foreach (explode(',', $act->image) as $img) {
                try { Storage::disk('s3')->delete(trim($img)); } catch (\Exception $e) {}
            }
        }
        DB::table('activities')->where('id', $id)->delete();
        $this->logActivity('deleted', 'activity', $id, ['title' => $act->title ?? 'Unknown']);
        return redirect()->route('admin.activities')->with('success', 'Activity deleted successfully');
    }

    // ===== الأخبار =====
    public function news()
    {
        $news = DB::table('news')->orderBy('created_at', 'desc')->get();
        return view('admin.news.index', compact('news'));
    }

    public function newsCreate()
    {
        if (!$this->hasPermission('manage_news')) abort(403);
        return view('admin.news.form', ['item' => null]);
    }

    public function newsStore(Request $request)
    {
        if (!$this->hasPermission('manage_news')) abort(403);
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'excerpt'  => 'required|string',
            'content'  => 'required|string',
            'images'   => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        // Remove images from data array (handled separately below)
        unset($data['images']);

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('news', 's3');
            }
        }

        // Set image field
        $data['image'] = !empty($imagePaths) ? implode(',', $imagePaths) : null;

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['published_at'] = $data['is_published'] ? now() : null;
        $data['created_at']   = now();
        $data['updated_at']   = now();

        $newsId = DB::table('news')->insertGetId($data);
        $this->logActivity('created', 'news', $newsId, ['title' => $data['title']]);
        return redirect()->route('admin.news')->with('success', 'تم إضافة الخبر بنجاح');
    }

    public function newsEdit($id)
    {
        if (!$this->hasPermission('manage_news')) abort(403);
        $item = DB::table('news')->find($id);
        return view('admin.news.form', compact('item'));
    }

    public function newsUpdate(Request $request, $id)
    {
        if (!$this->hasPermission('manage_news')) abort(403);
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'excerpt'         => 'required|string',
            'content'         => 'required|string',
            'images'          => 'nullable|array',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            'existing_images' => 'nullable|array',
        ]);

        // Get existing images to keep & remove from data before DB update
        $existingImages = $request->input('existing_images', []);
        unset($data['images'], $data['existing_images']);

        // Handle new images
        $newImagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $newImagePaths[] = $image->store('news', 's3');
            }
        }

        // Combine existing and new images
        $allImages = array_merge($existingImages, $newImagePaths);
        $data['image'] = !empty($allImages) ? implode(',', $allImages) : null;

        // Delete old images that were removed
        $oldNews = DB::table('news')->find($id);
        if ($oldNews && $oldNews->image) {
            $oldImages = explode(',', $oldNews->image);
            foreach ($oldImages as $oldImage) {
                if (!in_array(trim($oldImage), $existingImages)) {
                    try { Storage::disk('s3')->delete(trim($oldImage)); } catch (\Exception $e) {}
                }
            }
        }

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['updated_at']   = now();

        DB::table('news')->where('id', $id)->update($data);
        $this->logActivity('updated', 'news', $id, ['title' => $data['title']]);
        return redirect()->route('admin.news')->with('success', 'تم تعديل الخبر بنجاح');
    }

    public function newsDelete($id)
    {
        if (!$this->hasPermission('manage_news')) abort(403);
        $news = DB::table('news')->find($id);
        if ($news && $news->image) {
            foreach (explode(',', $news->image) as $img) {
                try { Storage::disk('s3')->delete(trim($img)); } catch (\Exception $e) {}
            }
        }
        DB::table('news')->where('id', $id)->delete();
        $this->logActivity('deleted', 'news', $id, ['title' => $news->title ?? 'Unknown']);
        return redirect()->route('admin.news')->with('success', 'News deleted successfully');
    }

    // ===== الإعدادات =====
    public function settings()
    {
        $settings = DB::table('tribe_settings')->first();
        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $data = $request->validate([
            'tribe_name'        => 'required|string|max:255',
            'tribe_description' => 'nullable|string',
            'founded_date'      => 'nullable|date',
            'location'           => 'nullable|string|max:255',
            'contact_email'     => 'nullable|email|max:255',
            'contact_phone'     => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'cover_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $settings = DB::table('tribe_settings')->first();

        if ($request->hasFile('logo')) {
            if ($settings && $settings->logo) { try { Storage::disk('s3')->delete($settings->logo); } catch (\Exception $e) {} }
            $data['logo'] = $request->file('logo')->store('settings', 's3');
        }
        if ($request->hasFile('cover_image')) {
            if ($settings && $settings->cover_image) { try { Storage::disk('s3')->delete($settings->cover_image); } catch (\Exception $e) {} }
            $data['cover_image'] = $request->file('cover_image')->store('settings', 's3');
        }

        if ($settings) {
            DB::table('tribe_settings')->where('id', $settings->id)->update($data);
        } else {
            DB::table('tribe_settings')->insert($data);
        }

        return redirect()->route('admin.settings')->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    public function passwordEdit()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);

        $admin = DB::table('admins')->where('id', session('admin_id'))->first();

        if (!$admin || !\Illuminate\Support\Facades\Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        DB::table('admins')->where('id', $admin->id)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return redirect()->route('admin.settings')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    // ===== إدارة المستخدمين (المدراء) =====

    private function isSuperAdmin()
    {
        $admin = DB::table('admins')->where('id', session('admin_id'))->first();
        return $admin && $admin->role === 'super_admin';
    }

    public function users()
    {
        if (!$this->isSuperAdmin()) abort(403, 'غير مصرح لك بدخول هذه الصفحة');
        $users = DB::table('admins')->get();
        return view('admin.users.index', compact('users'));
    }

    public function userCreate()
    {
        if (!$this->isSuperAdmin()) abort(403);
        $user = null;
        return view('admin.users.form', compact('user'));
    }

    public function userStore(Request $request)
    {
        if (!$this->isSuperAdmin()) abort(403);
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:admins,email',
            'password'    => 'required|string|min:6',
            'permissions' => 'nullable|array'
        ]);

        DB::table('admins')->insert([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role'       => 'sub_admin',
            'permissions'=> json_encode($data['permissions'] ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.users')->with('success', 'تمت إضافة المستخدم بنجاح');
    }

    public function userEdit($id)
    {
        if (!$this->isSuperAdmin()) abort(403);
        $user = DB::table('admins')->where('id', $id)->first();
        if (!$user) return redirect()->route('admin.users')->with('error', 'المستخدم غير موجود');
        
        $user->permissions = json_decode($user->permissions, true) ?? [];
        return view('admin.users.form', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        if (!$this->isSuperAdmin()) abort(403);
        
        $user = DB::table('admins')->where('id', $id)->first();
        if (!$user) return redirect()->route('admin.users')->with('error', 'المستخدم غير موجود');
        
        if ($user->role === 'super_admin' && $id != session('admin_id')) {
            return back()->with('error', 'لا يمكن التعديل على الصلاحيات الخاصة بالمدير العام');
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => "required|email|unique:admins,email,{$id}",
            'password'    => 'nullable|string|min:6',
            'permissions' => 'nullable|array'
        ]);

        $updateData = [
            'name'       => $data['name'],
            'email'      => $data['email'],
            'updated_at' => now(),
        ];

        if ($user->role !== 'super_admin') {
            $updateData['permissions'] = json_encode($data['permissions'] ?? []);
        }

        if (!empty($data['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        DB::table('admins')->where('id', $id)->update($updateData);

        return redirect()->route('admin.users')->with('success', 'تم تحديث بيانات المستخدم بنجاح');
    }

    public function userDelete($id)
    {
        if (!$this->isSuperAdmin()) abort(403);
        
        $user = DB::table('admins')->where('id', $id)->first();
        if (!$user) return back()->with('error', 'المستخدم غير موجود');
        
        if ($user->role === 'super_admin') {
            return back()->with('error', 'لا يمكن حذف المدير العام للنظام!');
        }

        DB::table('admins')->where('id', $id)->delete();
        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    // ===== سجل النشاطات =====
    public function activityLogs()
    {
        if (!$this->hasPermission('super_admin')) abort(403);
        $logs = DB::table('admin_activity_logs')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.activity_logs', compact('logs'));
    }

    public function clearActivityLogs()
    {
        if (!$this->hasPermission('super_admin')) abort(403);
        DB::table('admin_activity_logs')->truncate();
        return redirect()->route('admin.logs')->with('success', 'تم حذف السجل بالكامل بنجاح');
    }
}
