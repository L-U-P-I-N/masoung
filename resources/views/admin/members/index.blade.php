@extends('layouts.admin')

@section('title', 'الأعضاء')
@section('page-title', 'إدارة الأعضاء')
@section('breadcrumb', 'الرئيسية / الأعضاء')

@section('topbar-actions')
    @php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    @endphp
    @if($isSuper || in_array('manage_members', $perms))
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        <span class="topbar-btn-text">إضافة عضو</span>
    </a>
    @endif
@endsection

@push('styles')
<style>
    /* ===== BADGE PENDING ===== */
    .badge-pending {
        background: rgba(234,179,8,0.12);
        color: #fde047;
        border: 1px solid rgba(234,179,8,0.25);
    }

    /* ===== BULK BAR ===== */
    .bulk-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.6rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: rgba(201,168,76,0.03);
    }
    .bulk-bar-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* ===== PENDING ROW HIGHLIGHT ===== */
    tr.row-pending td {
        background: rgba(234,179,8,0.03);
    }
    tr.row-pending:hover td {
        background: rgba(234,179,8,0.06) !important;
    }

    /* ===== ACTION BUTTONS CELL ===== */
    .actions-cell {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    /* Approve green btn */
    .btn-approve {
        background: rgba(34,197,94,0.12);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.3);
    }
    .btn-approve:hover { background: rgba(34,197,94,0.22); }

    /* Reject red btn */
    .btn-reject {
        background: rgba(239,68,68,0.1);
        color: #fca5a5;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .btn-reject:hover { background: rgba(239,68,68,0.2); }

    /* ===== MEMBER AVATAR ===== */
    .member-avatar {
        width: 40px; height: 40px;
        border-radius: 10px;
        object-fit: cover;
        background: var(--dark3);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: 1rem;
        overflow: hidden; flex-shrink: 0;
    }
    .member-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .member-name-cell {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* ===== MOBILE CARDS ===== */
    .members-cards { display: none; flex-direction: column; gap: 0.75rem; }
    .member-card {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        transition: border-color 0.2s;
    }
    .member-card.pending-card {
        border-color: rgba(234,179,8,0.2);
        background: rgba(234,179,8,0.02);
    }
    .member-card-top {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }
    .member-card-top .member-avatar {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
    }
    .member-card-info { flex: 1; min-width: 0; }
    .member-card-info .name { font-weight: 700; font-size: 0.95rem; }
    .member-card-info .sub {
        font-size: 0.78rem; color: var(--text-muted);
        margin-top: 0.1rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .member-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(201,168,76,0.08);
        flex-wrap: wrap;
    }
    .member-card-actions { display: flex; gap: 0.4rem; flex-wrap: wrap; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 720px) {
        .members-table-wrap { display: none; }
        .members-cards { display: flex; }
        .topbar-btn-text { display: none; }
        .bulk-bar { display: none; } /* hide bulk on mobile, use card actions */
    }
    @media (max-width: 400px) {
        .member-card { padding: 0.85rem; }
    }
</style>
@endpush

@section('content')

@php
    $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
    $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
    $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    $canManage = $isSuper || in_array('manage_members', $perms);

    $pendingCount = $members->where('is_active', 0)->count();
    $activeCount  = $members->where('is_active', 1)->count();
@endphp

{{-- ===== PENDING ALERT ===== --}}
@if($pendingCount > 0)
<div class="alert" style="background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.25);color:#fde047;margin-bottom:1.25rem">
    <i class="fas fa-clock"></i>
    يوجد <strong>{{ $pendingCount }}</strong> {{ $pendingCount === 1 ? 'طلب عضوية' : 'طلبات عضوية' }} بانتظار المراجعة
</div>
@endif

{{-- ===== DESKTOP TABLE ===== --}}
<div class="table-wrap members-table-wrap">

    {{-- Bulk Actions Bar --}}
    @if($canManage && $pendingCount > 0)
    <div class="bulk-bar">
        <div class="bulk-bar-right">
            <form action="{{ route('admin.members.approve.all') }}" method="POST"
                  onsubmit="return confirm('الموافقة على جميع الأعضاء المنتظرين؟')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-approve" style="font-size:0.82rem">
                    <i class="fas fa-check-double"></i> قبول الكل ({{ $pendingCount }})
                </button>
            </form>
            <button type="submit" form="bulkApproveForm"
                    class="btn btn-edit" style="font-size:0.82rem">
                <i class="fas fa-check"></i> قبول المحدد
            </button>
        </div>
        <span style="font-size:0.78rem;color:var(--text-muted)">
            {{ $activeCount }} عضو نشط، {{ $pendingCount }} بانتظار المراجعة
        </span>
    </div>
    @endif

    <div class="table-head">
        <h2>قائمة الأعضاء ({{ $members->count() }})</h2>
    </div>

    @if($members->isEmpty())
        <div class="empty">
            <i class="fas fa-users"></i>
            لا يوجد أعضاء حتى الآن
            <br><br>
            @if($canManage)
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">إضافة أول عضو</a>
            @endif
        </div>
    @else
    <div class="table-responsive">
    <form id="bulkApproveForm" action="{{ route('admin.members.approve.bulk') }}" method="POST"
          onsubmit="return confirm('الموافقة على الأعضاء المحددين؟')">
        @csrf @method('PATCH')
    <table>
        <thead>
            <tr>
                @if($canManage && $pendingCount > 0)
                <th style="width:36px"><input type="checkbox" id="selectAll"></th>
                @endif
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>المنصب</th>
                <th>المهنة</th>
                <th>الهاتف</th>
                <th>الحالة</th>
                @if($canManage)
                <th style="text-align:center">الإجراءات</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
            <tr class="{{ !$m->is_active ? 'row-pending' : '' }}">

                {{-- Checkbox (للانتظار فقط) --}}
                @if($canManage && $pendingCount > 0)
                <td>
                    @if(!$m->is_active)
                        <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" class="member-checkbox">
                    @endif
                </td>
                @endif

                <td style="color:var(--text-muted);font-size:0.78rem">{{ $m->id }}</td>

                {{-- الصورة --}}
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
                <td style="color:var(--text-muted);font-size:0.85rem">{{ $m->position ?? 'عضو' }}</td>
                <td style="color:var(--text-muted);font-size:0.85rem">{{ $m->profession ?? '—' }}</td>
                <td style="direction:ltr;font-size:0.83rem;color:var(--text-muted)">{{ $m->phone ?? '—' }}</td>

                {{-- الحالة --}}
                <td>
                    @if($m->is_active)
                        <span class="badge badge-green"><i class="fas fa-check-circle" style="font-size:0.7rem"></i> نشط</span>
                    @else
                        <span class="badge badge-pending"><i class="fas fa-clock" style="font-size:0.7rem"></i> بانتظار المراجعة</span>
                    @endif
                </td>

                {{-- الإجراءات --}}
                @if($canManage)
                <td>
                    <div class="actions-cell">
                        @if(!$m->is_active)
                            {{-- عضو منتظر → موافقة أو رفض فقط --}}
                            <form action="{{ route('admin.members.approve', $m->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-approve" title="قبول الطلب">
                                    <i class="fas fa-check"></i> قبول
                                </button>
                            </form>
                            <form action="{{ route('admin.members.reject', $m->id) }}" method="POST"
                                  onsubmit="return confirm('رفض طلب عضوية {{ addslashes($m->name) }}؟ سيتم حذف بياناته نهائياً.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-reject" title="رفض الطلب">
                                    <i class="fas fa-times"></i> رفض
                                </button>
                            </form>
                        @else
                            {{-- عضو نشط → تعديل وحذف فقط --}}
                            <a href="{{ route('admin.members.edit', $m->id) }}" class="btn btn-edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.members.delete', $m->id) }}" method="POST"
                                  onsubmit="return confirm('حذف عضو {{ addslashes($m->name) }}؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-del" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>
    </form>
    </div>
    @endif
</div>

{{-- ===== MOBILE CARDS ===== --}}
<div class="members-cards">
    @forelse($members as $m)
    <div class="member-card {{ !$m->is_active ? 'pending-card' : '' }}">
        <div class="member-card-top">
            <div class="member-avatar">
                @if($m->photo)
                    <img src="{{ Storage::url($m->photo) }}" alt="{{ $m->name }}">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="member-card-info">
                <div class="name">{{ $m->name }}</div>
                <div class="sub">{{ $m->profession ?? $m->position ?? 'عضو' }}</div>
            </div>
            {{-- حالة العضو --}}
            @if($m->is_active)
                <span class="badge badge-green" style="white-space:nowrap"><i class="fas fa-check-circle" style="font-size:0.68rem"></i> نشط</span>
            @else
                <span class="badge badge-pending" style="white-space:nowrap"><i class="fas fa-clock" style="font-size:0.68rem"></i> انتظار</span>
            @endif
        </div>

        @if($canManage)
        <div class="member-card-meta">
            <span style="font-size:0.76rem;color:var(--text-muted)">
                <i class="fas fa-phone" style="font-size:0.68rem"></i>
                {{ $m->phone ?? '—' }}
            </span>
            <div class="member-card-actions">
                @if(!$m->is_active)
                    {{-- منتظر → موافقة أو رفض --}}
                    <form action="{{ route('admin.members.approve', $m->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-approve" style="padding:0.38rem 0.65rem;font-size:0.78rem">
                            <i class="fas fa-check"></i> قبول
                        </button>
                    </form>
                    <form action="{{ route('admin.members.reject', $m->id) }}" method="POST"
                          onsubmit="return confirm('رفض طلب {{ addslashes($m->name) }}؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-reject" style="padding:0.38rem 0.65rem;font-size:0.78rem">
                            <i class="fas fa-times"></i> رفض
                        </button>
                    </form>
                @else
                    {{-- نشط → تعديل وحذف --}}
                    <a href="{{ route('admin.members.edit', $m->id) }}"
                       class="btn btn-edit" style="padding:0.38rem 0.65rem;font-size:0.78rem">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.members.delete', $m->id) }}" method="POST"
                          onsubmit="return confirm('حذف عضو {{ addslashes($m->name) }}؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-del" style="padding:0.38rem 0.65rem;font-size:0.78rem">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif
    </div>
    @empty
    <div class="empty">
        <i class="fas fa-users"></i>
        لا يوجد أعضاء حتى الآن
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function () {
    document.querySelectorAll('.member-checkbox')
            .forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
