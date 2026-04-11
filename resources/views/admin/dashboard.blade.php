@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')
@section('breadcrumb', 'الرئيسية / لوحة التحكم')

@section('content')

{{-- إحصاءات --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(201,168,76,0.15);color:var(--gold)"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-num">{{ $stats['members'] }}</div>
            <div class="stat-lbl">الأعضاء</div>
        </div>
    </div>
    @if($stats['pending_members'] > 0)
    <div class="stat-card" style="border: 1px solid rgba(239,68,68,0.3); background: rgba(239,68,68,0.05);">
        <div class="stat-icon" style="background:rgba(239,68,68,0.15);color:#fca5a5"><i class="fas fa-user-clock"></i></div>
        <div>
            <div class="stat-num">{{ $stats['pending_members'] }}</div>
            <div class="stat-lbl" style="color:#fca5a5">بانتظار الموافقة</div>
        </div>
    </div>
    @endif
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,0.15);color:#93c5fd"><i class="fas fa-newspaper"></i></div>
        <div>
            <div class="stat-num">{{ $stats['news'] }}</div>
            <div class="stat-lbl">الأخبار</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:var(--green)"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-num">{{ $stats['activities'] }}</div>
            <div class="stat-lbl">الأنشطة</div>
        </div>
    </div>
</div>

{{-- روابط سريعة --}}
@php
    $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
    $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
    $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem">
    @if($isSuper || in_array('manage_members', $perms))
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-user-plus"></i> إضافة عضو
    </a>
    @endif
    
    @if($isSuper || in_array('manage_activities', $perms))
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-plus-circle"></i> إضافة نشاط
    </a>
    @endif
    
    @if($isSuper || in_array('manage_news', $perms))
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-edit"></i> إضافة خبر
    </a>
    @endif
</div>


<div class="stat-grid" style="margin-bottom: 1.5rem;">

    {{-- آخر الأخبار --}}
    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-newspaper" style="color:var(--gold);margin-left:0.5rem"></i>آخر الأخبار</h2>
            <a href="{{ route('admin.news') }}" class="btn btn-edit">عرض الكل</a>
        </div>
        @if($latestNews->isEmpty())
        <div class="empty"><i class="fas fa-newspaper"></i>لا توجد أخبار</div>
        @else
        <div class="table-responsive">
            <table>
                <thead><tr><th>العنوان</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                <tbody>
                    @foreach($latestNews as $n)
                    <tr>
                        <td>{{ Str::limit($n->title, 35) }}</td>
                        <td>
                            <span class="badge {{ $n->is_published ? 'badge-green' : 'badge-gray' }}">
                                {{ $n->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.8rem">{{ \Carbon\Carbon::parse($n->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- آخر الأنشطة --}}
    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-calendar-alt" style="color:var(--gold);margin-left:0.5rem"></i>آخر الأنشطة</h2>
            <a href="{{ route('admin.activities') }}" class="btn btn-edit">عرض الكل</a>
        </div>
        @if($latestActs->isEmpty())
        <div class="empty"><i class="fas fa-calendar-alt"></i>لا توجد أنشطة</div>
        @else
        <div class="table-responsive">
            <table>
                <thead><tr><th>النشاط</th><th>التاريخ</th><th>الحالة</th></tr></thead>
                <tbody>
                    @foreach($latestActs as $a)
                    <tr>
                        <td>{{ Str::limit($a->title, 30) }}</td>
                        <td style="color:var(--text-muted);font-size:0.8rem;direction:ltr">{{ \Carbon\Carbon::parse($a->activity_date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $a->is_published ? 'badge-green' : 'badge-gray' }}">
                                {{ $a->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>



@endsection
