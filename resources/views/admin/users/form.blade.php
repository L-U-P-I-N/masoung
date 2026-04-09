@extends('layouts.admin')

@section('title', $user ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد')

@section('content')
<div class="topbar">
    <div>
        <h1>{{ $user ? 'تعديل بيانات المستخدم: ' . $user->name : 'إضافة مستخدم جديد' }}</h1>
        <div class="breadcrumb"><a href="{{ route('admin.users') }}" style="color:inherit;text-decoration:none;">إدارة المستخدمين</a> / {{ $user ? 'تعديل' : 'إضافة' }}</div>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('admin.users') }}" class="btn" style="background:var(--dark3); border:1px solid var(--border); color:var(--text);"><i class="fas fa-arrow-right"></i> عودة</a>
    </div>
</div>

<div class="content">
    @if($errors->any())
        <div class="alert alert-error">يرجى التأكد من صحة البيانات المدخلة.</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="form-card" style="max-width:800px; margin:0 auto;">
        <form action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if($user) @method('PUT') @endif

            <div class="form-grid">
                <div class="form-group">
                    <label class="lbl">الاسم الكامل <span style="color:var(--red)">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="lbl">البريد الإلكتروني <span style="color:var(--red)">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group full">
                    <label class="lbl">كلمة المرور {{ $user ? '(اترك الحقل فارغاً إذا لم ترغب بتغييرها)' : '*' }}</label>
                    <input type="password" name="password" {{ $user ? '' : 'required' }}>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                
                @if(!$user || $user->role !== 'super_admin')
                <div class="form-group full" style="margin-top:1rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                    <label class="lbl" style="font-size:1rem; color:var(--gold); margin-bottom:1rem;"><i class="fas fa-key"></i> صلاحيات المشرف</label>
                    
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                        @php $perms = $user ? $user->permissions : []; @endphp
                        
                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_members" name="permissions[]" value="manage_members" {{ in_array('manage_members', $perms) ? 'checked' : '' }}>
                            <label for="perm_members">
                                <strong style="display:block; color:#fff;">إدارة الأعضاء</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">مراجعة، إضافة وتعديل حسابات القبيلة</span>
                            </label>
                        </div>

                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_news" name="permissions[]" value="manage_news" {{ in_array('manage_news', $perms) ? 'checked' : '' }}>
                            <label for="perm_news">
                                <strong style="display:block; color:#fff;">إدارة الأخبار</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">نشر، تعديل وحذف الأخبار</span>
                            </label>
                        </div>

                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_activities" name="permissions[]" value="manage_activities" {{ in_array('manage_activities', $perms) ? 'checked' : '' }}>
                            <label for="perm_activities">
                                <strong style="display:block; color:#fff;">إدارة الأنشطة</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">نشر وتحديث الفعاليات والأنشطة</span>
                            </label>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group full" style="margin-top:1rem; padding:1rem; background:rgba(212,175,55,0.1); border:1px dashed var(--gold); border-radius:10px; text-align:center;">
                    <i class="fas fa-crown" style="color:var(--gold); font-size:2rem; margin-bottom:0.5rem;"></i>
                    <p style="color:var(--gold); font-weight:bold; margin:0;">هذا الحساب هو "المدير العام" ويمتلك كافة الصلاحيات على النظام.</p>
                </div>
                @endif
            </div>

            <div style="margin-top:2rem; text-align:left;">
                <button type="submit" class="btn btn-primary" style="padding:0.8rem 2rem; font-size:1rem;"><i class="fas fa-save"></i> حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>
@endsection
