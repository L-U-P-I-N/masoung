@extends('layouts.admin')

@section('title', 'الأخبار')
@section('page-title', 'إدارة الأخبار')
@section('breadcrumb', 'الرئيسية / الأخبار')

@section('topbar-actions')
    @php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    @endphp

    @if($isSuper || in_array('manage_news', $perms))
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة خبر
    </a>
    @endif
@endsection

@section('content')
<div class="table-wrap">
    <div class="table-head">
        <h2>قائمة الأخبار ({{ count($news) }})</h2>
    </div>
    @if($news->isEmpty())
        <div class="empty">
            <i class="fas fa-newspaper"></i>
            لا توجد أخبار حتى الآن
            <br><br>
            @if($isSuper || in_array('manage_news', $perms))
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">إضافة أول خبر</a>
            @endif
        </div>
    @else
    <div style="overflow-x:auto">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>عنوان الخبر</th>
                <th>الملخص</th>
                <th>الحالة</th>
                <th>تاريخ النشر</th>
                @if($isSuper || in_array('manage_news', $perms))
                <th>الإجراءات</th>
                @else
                <th>عرض</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($news as $n)
            <tr>
                <td style="color:var(--text-muted);font-size:0.8rem">{{ $n->id }}</td>
                <td>
                    <div class="td-img" style="position:relative">
                        @if($n->image)
                            @php $firstImg = trim(explode(',', $n->image)[0]); $imgCount = count(array_filter(explode(',', $n->image))); @endphp
                            <img src="{{ Storage::url($firstImg) }}" alt="">
                            @if($imgCount > 1)
                                <span style="position:absolute;bottom:0;right:0;background:rgba(0,0,0,0.65);color:#fff;font-size:10px;padding:1px 5px;border-radius:3px 0 0 0">+{{ $imgCount }}</span>
                            @endif
                        @else
                            <i class="fas fa-newspaper"></i>
                        @endif
                    </div>
                </td>
                <td><strong>{{ Str::limit($n->title, 40) }}</strong></td>
                <td style="color:var(--text-muted);font-size:0.82rem">{{ Str::limit($n->excerpt, 60) }}</td>
                <td>
                    <span class="badge {{ $n->is_published ? 'badge-green' : 'badge-gray' }}">
                        {{ $n->is_published ? 'منشور' : 'مسودة' }}
                    </span>
                </td>
                <td style="font-size:0.82rem;color:var(--text-muted);direction:ltr">
                    {{ $n->published_at ? \Carbon\Carbon::parse($n->published_at)->format('d/m/Y') : '—' }}
                </td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        <a href="{{ route('news.show', $n->id) }}" target="_blank" class="btn btn-edit" title="معاينة">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($isSuper || in_array('manage_news', $perms))
                        <a href="{{ route('admin.news.edit', $n->id) }}" class="btn btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="{{ route('admin.news.delete', $n->id) }}"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">
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
