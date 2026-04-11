@extends('layouts.admin')

@section('title', $user ? 'تعديل مدير النظام' : 'إضافة مدير جديد')
@section('page-title', $user ? 'تعديل: ' . $user->name : 'إضافة مدير جديد')
@section('breadcrumb', 'الرئيسية / مدراء النظام / ' . ($user ? 'تعديل' : 'إضافة'))

@section('topbar-actions')
    <a href="{{ route('admin.users') }}" class="btn btn-edit">
        <i class="fas fa-arrow-right"></i>
        <span class="btn-back-text">رجوع</span>
    </a>
@endsection

@push('styles')
<style>
    /* ===== USER FORM PAGE ===== */
    .user-form-wrap {
        max-width: 820px;
        margin: 0 auto;
    }

    /* Section Card */
    .section-card {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .section-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: rgba(201,168,76,0.04);
    }
    .section-card-header .section-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(201,168,76,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        font-size: 0.88rem;
        flex-shrink: 0;
    }
    .section-card-header h3 {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text);
    }
    .section-card-body {
        padding: 1.25rem;
    }

    /* Form Grid */
    .uf-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .uf-grid .full { grid-column: 1 / -1; }

    /* Permission Cards */
    .perm-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.85rem;
    }
    .perm-card {
        background: var(--dark3);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        position: relative;
    }
    .perm-card:hover {
        border-color: rgba(201,168,76,0.3);
        background: rgba(201,168,76,0.05);
    }
    .perm-card input[type="checkbox"] {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        width: 17px;
        height: 17px;
        accent-color: var(--gold);
        cursor: pointer;
    }
    .perm-card:has(input:checked) {
        border-color: rgba(201,168,76,0.45);
        background: rgba(201,168,76,0.07);
    }
    .perm-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(201,168,76,0.1);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .perm-card label {
        cursor: pointer;
    }
    .perm-card label strong {
        display: block;
        font-size: 0.87rem;
        color: var(--text);
        margin-bottom: 0.15rem;
        font-weight: 700;
    }
    .perm-card label span {
        font-size: 0.72rem;
        color: var(--text-muted);
        line-height: 1.4;
    }

    /* Super admin notice */
    .super-notice {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(201,168,76,0.06);
        border: 1px dashed rgba(201,168,76,0.35);
        border-radius: 12px;
        color: var(--gold);
    }
    .super-notice i { font-size: 1.5rem; flex-shrink: 0; }
    .super-notice p { font-size: 0.88rem; margin: 0; font-weight: 600; }

    /* Form Actions */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        justify-content: flex-start;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }
    .btn-save {
        padding: 0.7rem 2rem;
        font-size: 0.95rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 700px) {
        .uf-grid {
            grid-template-columns: 1fr;
        }
        .uf-grid .full { grid-column: 1; }
        .perm-grid { grid-template-columns: 1fr 1fr; }
        .btn-back-text { display: none; }
    }
    @media (max-width: 480px) {
        .perm-grid { grid-template-columns: 1fr; }
        .section-card-body { padding: 1rem; }
        .btn-save { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<div class="user-form-wrap">

    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        يرجى التأكد من صحة البيانات المدخلة.
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
        @csrf
        @if($user) @method('PUT') @endif

        {{-- ===== BASIC INFO ===== --}}
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-icon"><i class="fas fa-user"></i></div>
                <h3>البيانات الأساسية</h3>
            </div>
            <div class="section-card-body">
                <div class="uf-grid">

                    <div class="form-group">
                        <label class="lbl">الاسم الكامل <span style="color:var(--red)">*</span></label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name ?? '') }}"
                               placeholder="أدخل الاسم الكامل"
                               required>
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="lbl">البريد الإلكتروني <span style="color:var(--red)">*</span></label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email ?? '') }}"
                               placeholder="example@domain.com"
                               style="direction:ltr"
                               required>
                        @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group full">
                        <label class="lbl">
                            كلمة المرور
                            @if($user)
                                <span style="color:var(--text-muted);font-size:0.76rem;font-weight:400">
                                    — اتركها فارغة إذا لم ترغب بتغييرها
                                </span>
                            @else
                                <span style="color:var(--red)">*</span>
                            @endif
                        </label>
                        <div style="position:relative">
                            <input type="password" name="password"
                                   id="passwordField"
                                   placeholder="{{ $user ? '••••••••' : 'كلمة مرور قوية...' }}"
                                   style="padding-left:2.8rem; direction:ltr"
                                   {{ $user ? '' : 'required' }}>
                            <button type="button" onclick="togglePassword()"
                                    style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);
                                           background:none;border:none;color:var(--text-muted);cursor:pointer;
                                           font-size:0.9rem;padding:0" tabindex="-1">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== PERMISSIONS ===== --}}
        @if(!$user || $user->role !== 'super_admin')
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-icon"><i class="fas fa-key"></i></div>
                <h3>صلاحيات المشرف</h3>
            </div>
            <div class="section-card-body">
                @php $perms = $user ? (json_decode($user->permissions, true) ?: []) : []; @endphp
                <div class="perm-grid">

                    <div class="perm-card">
                        <input type="checkbox" id="perm_members" name="permissions[]"
                               value="manage_members"
                               {{ in_array('manage_members', $perms) ? 'checked' : '' }}>
                        <div class="perm-icon"><i class="fas fa-users"></i></div>
                        <label for="perm_members">
                            <strong>إدارة الأعضاء</strong>
                            <span>مراجعة وإضافة وتعديل أعضاء القبيلة</span>
                        </label>
                    </div>

                    <div class="perm-card">
                        <input type="checkbox" id="perm_news" name="permissions[]"
                               value="manage_news"
                               {{ in_array('manage_news', $perms) ? 'checked' : '' }}>
                        <div class="perm-icon"><i class="fas fa-newspaper"></i></div>
                        <label for="perm_news">
                            <strong>إدارة الأخبار</strong>
                            <span>نشر وتعديل وحذف الأخبار</span>
                        </label>
                    </div>

                    <div class="perm-card">
                        <input type="checkbox" id="perm_activities" name="permissions[]"
                               value="manage_activities"
                               {{ in_array('manage_activities', $perms) ? 'checked' : '' }}>
                        <div class="perm-icon"><i class="fas fa-calendar-alt"></i></div>
                        <label for="perm_activities">
                            <strong>إدارة الأنشطة</strong>
                            <span>نشر وتحديث الفعاليات والأنشطة</span>
                        </label>
                    </div>

                </div>
            </div>
        </div>
        @else
        <div class="section-card">
            <div class="section-card-header">
                <div class="section-icon"><i class="fas fa-crown"></i></div>
                <h3>صلاحيات الحساب</h3>
            </div>
            <div class="section-card-body">
                <div class="super-notice">
                    <i class="fas fa-crown"></i>
                    <p>هذا الحساب هو "المدير العام" ويمتلك كافة الصلاحيات على النظام بشكل تلقائي.</p>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== FORM ACTIONS ===== --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-save">
                <i class="fas fa-save"></i>
                {{ $user ? 'حفظ التعديلات' : 'إضافة المدير' }}
            </button>
            <a href="{{ route('admin.users') }}" class="btn btn-edit">
                <i class="fas fa-times"></i> إلغاء
            </a>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
function togglePassword() {
    const field = document.getElementById('passwordField');
    const icon  = document.getElementById('eyeIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endpush
