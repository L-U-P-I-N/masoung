@extends('layouts.admin')

@section('title', $member ? 'تعديل عضو' : 'إضافة عضو')
@section('page-title', $member ? 'تعديل عضو' : 'إضافة عضو جديد')
@section('breadcrumb', 'الرئيسية / الأعضاء / ' . ($member ? 'تعديل' : 'إضافة'))

@section('topbar-actions')
    <a href="{{ route('admin.members') }}" class="btn btn-edit">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
@endsection

@section('content')

<form method="POST"
    action="{{ $member ? route('admin.members.update', $member->id) : route('admin.members.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if($member) @method('PUT') @endif

    <div class="form-card">
        <div class="form-grid">

            <div class="form-group">
                <label class="lbl">الاسم الكامل <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" placeholder="الاسم الثلاثي أو الرباعي" required>
                @error('name')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">Profession <span style="color:var(--gray);font-size:0.85rem">(Job/Career)</span></label>
                <input type="text" name="profession" value="{{ old('profession', $member->profession ?? '') }}" placeholder="e.g., Engineer, Doctor, Teacher...">
                @error('profession')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            {{-- Location field removed as it's redundant with Country/Province/City --}}

            <div class="form-group">
                <label class="lbl">المنصب / الدور <span style="color:var(--gray);font-size:0.85rem">(Tribal Role)</span></label>
                <input type="text" name="position" value="{{ old('position', $member->position ?? '') }}" placeholder="مثال: رئيس القبيلة، أمين الصندوق...">
                @error('position')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">الدولة <span style="color:var(--red)">*</span></label>
                <input type="text" name="country" id="country-input" list="country-list" value="{{ old('country', $member->country ?? 'اليمن') }}" placeholder="ابحث أو اكتب اسم الدولة..." required oninput="updatePhoneValidation()">
                <datalist id="country-list">
                    <!-- JS will populate this -->
                </datalist>
                @error('country')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">المحافظة <span style="color:var(--red)">*</span></label>
                <input type="text" name="province" value="{{ old('province', $member->province ?? '') }}" required placeholder="بغداد / صنعاء...">
                @error('province')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">المدينة <span style="color:var(--red)">*</span></label>
                <input type="text" name="city" value="{{ old('city', $member->city ?? '') }}" required placeholder="الكرادة / حدة...">
                @error('city')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group" id="phone-container">
                <label class="lbl">رقم الهاتف <span id="phone-hint" style="font-size:0.7rem; color:var(--text-muted)">(أدخل الرقم المحلي)</span> <button type="button" class="btn" style="background:var(--dark3); color:var(--gold); border:1px solid var(--border); border-radius:5px; padding:0 0.5rem; margin-right: 0.5rem; font-size: 0.7rem;" onclick="addPhoneField()">+</button></label>
                @php
                    $rawPhones = old('phone', ($member && $member->phone) ? explode(', ', $member->phone) : ['']);
                    if (empty($rawPhones)) $rawPhones = [''];
                @endphp
                @foreach($rawPhones as $p)
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                    <input type="tel" name="phone[]" class="phone-input" value="{{ $p }}" placeholder="+967 7XXXXXXXX" style="direction:ltr; flex:1" required>
                    @if(!$loop->first)
                    <button type="button" class="btn" style="background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); border-radius:10px; padding:0 0.8rem;" onclick="this.parentElement.remove()">×</button>
                    @endif
                </div>
                @endforeach
                @error('phone.*')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">ترتيب العرض</label>
                <input type="text" name="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" placeholder="0">
                @error('sort_order')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">صورة العضو</label>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                @if($member && $member->photo)
                <div style="margin-top:0.7rem">
                    <img src="{{ Storage::url($member->photo) }}" class="img-preview" alt="الصورة الحالية">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.3rem">الصورة الحالية — ارفع صورة جديدة للاستبدال</p>
                </div>
                @endif
                @error('photo')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group full">
                <label class="lbl">نبذة تعريفية</label>
                <textarea name="bio" placeholder="نبذة مختصرة عن العضو...">{{ old('bio', $member->bio ?? '') }}</textarea>
                @error('bio')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <div class="toggle-wrap">
                    <input type="checkbox" id="is_active" name="is_active"
                        {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="lbl" style="color:var(--text)">عضو نشط (يظهر في القائمة)</label>
                </div>
            </div>

        </div>

        <div style="border-top:1px solid var(--border);margin-top:1.5rem;padding-top:1.5rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-save"></i>
                {{ $member ? 'حفظ التعديلات' : 'إضافة العضو' }}
            </button>
            <a href="{{ route('admin.members') }}" class="btn btn-edit" style="padding:0.75rem 1.5rem">إلغاء</a>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function addPhoneField() {
    const container = document.getElementById('phone-container');
    const div = document.createElement('div');
    div.style.display = 'flex'; div.style.gap = '0.5rem'; div.style.marginBottom = '0.5rem';
    
    div.innerHTML = `
        <input type="tel" name="phone[]" class="phone-input" placeholder="+967 7XXXXXXXX" style="direction:ltr; flex:1" required>
        <button type="button" class="btn" style="background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); border-radius:10px; padding:0 0.8rem;" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

// Remove old validation logic
function updatePhoneValidation() {}
</script>
@endpush
