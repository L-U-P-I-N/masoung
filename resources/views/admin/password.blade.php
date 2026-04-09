@extends('layouts.admin')

@section('title', 'تغيير كلمة المرور')
@section('page-title', 'تغيير كلمة المرور')
@section('breadcrumb', 'الرئيسية / الإعدادات / تغيير كلمة المرور')

@section('content')
<style>
    .password-wrapper { position:relative; }
    .password-wrapper input { padding-left:2.8rem; }
    .toggle-password {
        position:absolute; left:0.8rem; top:50%; transform:translateY(-50%);
        background:none; border:none; color:var(--text-muted); cursor:pointer;
        font-size:1rem; padding:0.2rem; transition:color 0.2s;
    }
    .toggle-password:hover { color:var(--gold); }
</style>

<div class="form-card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);color:var(--gold)">
        <i class="fas fa-key" style="margin-left:0.5rem"></i> تعيين كلمة مرور جديدة
    </h2>

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="lbl">كلمة المرور الحالية <span style="color:var(--red)">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="current_password" required>
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('current_password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="password" required>
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">تأكيد كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="password_confirmation" required>
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);margin-top:1.5rem;padding-top:1.5rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-check-circle"></i> تحديث كلمة المرور
            </button>
            <a href="{{ route('admin.settings') }}" class="btn btn-secondary" style="padding:0.75rem 2rem;font-size:0.95rem;text-decoration:none;display:inline-flex;align-items:center;color:white;border:1px solid var(--border);border-radius:10px">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
function togglePass(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
