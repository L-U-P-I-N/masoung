@extends('layouts.admin')

@section('title', 'إدارة مدراء النظام')
@section('page-title', 'مدراء النظام')
@section('breadcrumb', 'الرئيسية / إدارة المستخدمين')

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        <span class="btn-text">إضافة مدير</span>
    </a>
@endsection

@push('styles')
<style>
    /* ===== USERS PAGE STYLES ===== */

    .users-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .users-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(201,168,76,0.1);
        border: 1px solid var(--border);
        color: var(--gold);
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
    }

    /* ===== DESKTOP TABLE ===== */
    .users-table-wrap {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }
    .users-table-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .users-table-head h2 {
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .users-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .users-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 520px;
    }
    .users-table th {
        padding: 0.75rem 1rem;
        background: var(--dark3);
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--text-muted);
        text-align: right;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    .users-table td {
        padding: 0.9rem 1rem;
        border-top: 1px solid rgba(201,168,76,0.07);
        font-size: 0.87rem;
        vertical-align: middle;
    }
    .users-table tr:hover td {
        background: rgba(201,168,76,0.03);
    }

    /* Avatar */
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark);
        flex-shrink: 0;
    }
    .user-name-cell {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .user-name-cell strong {
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* Role badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .role-super {
        background: rgba(201,168,76,0.15);
        color: var(--gold);
        border: 1px solid rgba(201,168,76,0.3);
    }
    .role-admin {
        background: rgba(59,130,246,0.12);
        color: #93c5fd;
        border: 1px solid rgba(59,130,246,0.25);
    }

    /* Actions */
    .action-btns {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* ===== MOBILE CARDS ===== */
    .users-cards {
        display: none;
        flex-direction: column;
        gap: 0.75rem;
    }
    .user-card {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        transition: border-color 0.2s;
    }
    .user-card:hover {
        border-color: rgba(201,168,76,0.35);
    }
    .user-card-top {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }
    .user-card-top .user-avatar {
        width: 44px;
        height: 44px;
        font-size: 1.05rem;
        border-radius: 12px;
    }
    .user-card-info {
        flex: 1;
        min-width: 0;
    }
    .user-card-info .name {
        font-weight: 700;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .user-card-info .email {
        font-size: 0.78rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 0.15rem;
    }
    .user-card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(201,168,76,0.08);
    }
    .user-card-meta .meta-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .meta-date {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* ===== EMPTY STATE ===== */
    .users-empty {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }
    .users-empty i {
        font-size: 3rem;
        display: block;
        margin-bottom: 1rem;
        color: var(--border);
    }
    .users-empty p {
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 680px) {
        .users-table-wrap { display: none; }
        .users-cards { display: flex; }
        .btn-text { display: none; }
    }
    @media (max-width: 400px) {
        .user-card { padding: 0.85rem; }
        .users-header { margin-bottom: 1rem; }
    }
</style>
@endpush

@section('content')

{{-- ===== HEADER ROW ===== --}}
<div class="users-header">
    <div class="users-count-badge">
        <i class="fas fa-user-shield"></i>
        {{ $users->count() }} مدير
    </div>
</div>

{{-- ===== DESKTOP TABLE ===== --}}
<div class="users-table-wrap">
    <div class="users-table-head">
        <h2>
            <i class="fas fa-user-shield" style="color:var(--gold)"></i>
            قائمة المدراء
        </h2>
    </div>

    @if($users->isEmpty())
    <div class="users-empty">
        <i class="fas fa-users-slash"></i>
        <p>لا يوجد مدراء حالياً</p>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة مدير جديد
        </a>
    </div>
    @else
    <div class="users-table-scroll">
        <table class="users-table">
            <thead>
                <tr>
                    <th style="width:44px">#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الرتبة</th>
                    <th>تاريخ الإضافة</th>
                    <th style="text-align:center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:0.78rem">{{ $i + 1 }}</td>
                    <td>
                        <div class="user-name-cell">
                            <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);direction:ltr;font-size:0.84rem">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'super_admin')
                            <span class="role-badge role-super"><i class="fas fa-crown"></i> مدير عام</span>
                        @else
                            <span class="role-badge role-admin"><i class="fas fa-shield-alt"></i> مشرف</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.82rem;direction:ltr">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content:center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->role !== 'super_admin')
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-del" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <span style="font-size:0.72rem;color:var(--text-muted);padding:0 0.3rem">محمي</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- ===== MOBILE CARDS ===== --}}
<div class="users-cards">
    @forelse($users as $user)
    <div class="user-card">
        <div class="user-card-top">
            <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
            <div class="user-card-info">
                <div class="name">{{ $user->name }}</div>
                <div class="email">{{ $user->email }}</div>
            </div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-edit" style="padding:0.4rem 0.6rem">
                <i class="fas fa-edit"></i>
            </a>
        </div>
        <div class="user-card-meta">
            <div class="meta-left">
                @if($user->role === 'super_admin')
                    <span class="role-badge role-super"><i class="fas fa-crown"></i> مدير عام</span>
                @else
                    <span class="role-badge role-admin"><i class="fas fa-shield-alt"></i> مشرف</span>
                @endif
                <span class="meta-date">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}
                </span>
            </div>
            @if($user->role !== 'super_admin')
            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-del" style="padding:0.4rem 0.6rem" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="users-empty">
        <i class="fas fa-users-slash"></i>
        <p>لا يوجد مدراء حالياً</p>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة مدير جديد
        </a>
    </div>
    @endforelse
</div>

@endsection
