@extends('layouts.admin')

@section('title', 'الأعضاء')
@section('page-title', 'إدارة الأعضاء')
@section('breadcrumb', 'الرئيسية / الأعضاء')

@section('topbar-actions')
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة عضو
    </a>
@endsection

@section('content')

<div class="table-wrap">
    <div class="table-head" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>قائمة الأعضاء ({{ count($members) }})</h2>
        <div style="display:flex; gap:0.5rem">
            <form action="{{ route('admin.members.approve.all') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الموافقة على جميع الأعضاء المنتظرين؟')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary" style="background:#10b981; padding:0.5rem 1rem">
                    <i class="fas fa-check-double"></i> قبول كل المنتظرين
                </button>
            </form>
            <button type="submit" form="bulkApproveForm" class="btn btn-primary" style="padding:0.5rem 1rem">
                <i class="fas fa-check"></i> قبول المحدد
            </button>
        </div>
    </div>

    @if($members->isEmpty())
        <div class="empty">
            <i class="fas fa-users"></i>
            لا يوجد أعضاء حتى الآن
            <br><br>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">إضافة أول عضو</a>
        </div>
    @else
    <div style="overflow-x:auto">
    <form id="bulkApproveForm" action="{{ route('admin.members.approve.bulk') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الموافقة على الأعضاء المحددين؟')">
        @csrf @method('PATCH')
    <table>
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>المنصب</th>
                <th>المهنة</th>
                <th>رقم الهاتف</th>
                <th>الموقع</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
            <tr>
                <td>
                    @if(!$m->is_active)
                        <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" class="member-checkbox">
                    @else
                        - 
                    @endif
                </td>
                <td style="color:var(--text-muted);font-size:0.8rem">{{ $m->id }}</td>
                <td>
                    <div class="td-img">
                        @if($m->photo)
                            <img src="{{ Storage::url($m->photo) }}" alt="{{ $m->name }}">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                </td>
                <td><strong>{{ $m->name }}</strong></td>
                <td style="color:var(--text-muted)">{{ $m->position ?? 'member' }}</td>
                <td style="color:var(--text-muted)">{{ $m->profession ?? '---' }}</td>
                <td style="direction:ltr;font-size:0.85rem">{{ $m->phone ?? '---' }}</td>
                <td style="color:var(--text-muted)">{{ $m->location ?? '---' }}</td>
                <td>
                    <span class="badge {{ $m->is_active ? 'badge-green' : 'badge-yellow' }}">
                        {{ $m->is_active ? 'نشط' : 'بانتظار الموافقة' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        @if(!$m->is_active)
                        <form action="{{ route('admin.members.approve', $m->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-primary" style="padding:0.3rem 0.6rem; font-size:0.75rem; background:var(--green)">
                                <i class="fas fa-check"></i> موافقة
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('admin.members.edit', $m->id) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="{{ route('admin.members.delete', $m->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-delete">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </form>
    </div>
    @endif
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

@endsection
