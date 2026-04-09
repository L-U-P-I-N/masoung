@extends('layouts.admin')

@section('title', 'إدارة مدراء النظام')

@section('content')
<div class="topbar">
    <div>
        <h1>مدراء النظام</h1>
        <div class="breadcrumb">لوحة التحكم / إدارة المستخدمين</div>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مدير جديد</a>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="form-card">
        <table style="width:100%; border-collapse:collapse; text-align:right;">
            <thead>
                <tr style="border-bottom:1px solid var(--border); color:var(--text-muted); font-size:0.85rem;">
                    <th style="padding:1rem;">الاسم</th>
                    <th style="padding:1rem;">البريد الإلكتروني</th>
                    <th style="padding:1rem;">الدور (الرتبة)</th>
                    <th style="padding:1rem;">تاريخ الإضافة</th>
                    <th style="padding:1rem; text-align:left;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom:1px solid var(--border); font-size:0.9rem;">
                    <td style="padding:1rem; font-weight:600;">{{ $user->name }}</td>
                    <td style="padding:1rem; color:var(--text-muted);">{{ $user->email }}</td>
                    <td style="padding:1rem;">
                        @if($user->role === 'super_admin')
                            <span style="background:rgba(212,175,55,0.2); color:var(--gold); padding:0.2rem 0.6rem; border-radius:4px; font-size:0.75rem; font-weight:bold;">مدير عام</span>
                        @else
                            <span style="background:rgba(59,130,246,0.2); color:#93c5fd; padding:0.2rem 0.6rem; border-radius:4px; font-size:0.75rem;">مشرف</span>
                        @endif
                    </td>
                    <td style="padding:1rem; color:var(--text-muted);">{{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</td>
                    <td style="padding:1rem; text-align:left;">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-edit" title="تعديل"><i class="fas fa-edit"></i></a>
                        @if($user->role !== 'super_admin')
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-del" title="حذف"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty">لا يوجد مدراء حالياً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
