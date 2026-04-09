@extends('layouts.admin')

@section('title', 'الأنشطة')
@section('page-title', 'إدارة الأنشطة')
@section('breadcrumb', 'الرئيسية / الأنشطة')

@section('topbar-actions')
    @php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    @endphp

    @if($isSuper || in_array('manage_activities', $perms))
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة نشاط
    </a>
    @endif
@endsection

@section('content')
<div class="table-wrap">
    <div class="table-head">
        <h2>قائمة الأنشطة ({{ count($activities) }})</h2>
    </div>
    @if($activities->isEmpty())
        <div class="empty">
            <i class="fas fa-calendar-times"></i>
            لا توجد أنشطة حتى الآن
            <br><br>
            @if($isSuper || in_array('manage_activities', $perms))
            <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">إضافة أول نشاط</a>
            @endif
        </div>
    @else
    <div style="overflow-x:auto">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>عنوان النشاط</th>
                <th>تاريخ النشاط</th>
                <th>الموقع</th>
                <th>الحالة</th>
                @if($isSuper || in_array('manage_activities', $perms))
                <th>الإجراءات</th>
                @else
                <th>عرض</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $a)
            <tr>
                <td style="color:var(--text-muted);font-size:0.8rem">{{ $a->id }}</td>
                <td>
                    <div class="td-img" style="position:relative">
                        @if($a->image)
                            @php $firstImg = trim(explode(',', $a->image)[0]); $imgCount = count(array_filter(explode(',', $a->image))); @endphp
                            <img src="{{ Storage::url($firstImg) }}" alt="">
                            @if($imgCount > 1)
                                <span style="position:absolute;bottom:0;right:0;background:rgba(0,0,0,0.65);color:#fff;font-size:10px;padding:1px 5px;border-radius:3px 0 0 0">+{{ $imgCount }}</span>
                            @endif
                        @else
                            <i class="fas fa-calendar-alt"></i>
                        @endif
                    </div>
                </td>
                <td><strong>{{ Str::limit($a->title, 40) }}</strong></td>
                <td style="font-size:0.85rem;direction:ltr">{{ \Carbon\Carbon::parse($a->activity_date)->format('d/m/Y') }}</td>
                <td style="color:var(--text-muted);font-size:0.85rem">{{ $a->location ?? '—' }}</td>
                <td>
                    <span class="badge {{ $a->is_published ? 'badge-green' : 'badge-gray' }}">
                        {{ $a->is_published ? 'منشور' : 'مسودة' }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        <a href="{{ route('activities.show', $a->id) }}" target="_blank" class="btn btn-edit" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($isSuper || in_array('manage_activities', $perms))
                        <a href="{{ route('admin.activities.edit', $a->id) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.activities.delete', $a->id) }}"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-del"><i class="fas fa-trash"></i></button>
                        </form>
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
@endsection
