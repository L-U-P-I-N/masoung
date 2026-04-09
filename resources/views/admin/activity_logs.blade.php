@extends('layouts.admin')

@section('title', 'سجل نشاطات المشرفين')
@section('page-title', 'سجل النشاطات')
@section('breadcrumb', 'الرئيسية / سجل النشاطات')

@section('content')
<div class="topbar">
    <div>
        <h1>سجل نشاطات المشرفين</h1>
        <div class="breadcrumb">لوحة التحكم / سجل النشاطات</div>
    </div>
    <div class="topbar-actions">
        <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من مسح جميع السجلات؟ هذا الإجراء لا يمكن التراجع عنه.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-del"><i class="fas fa-trash-alt"></i> إفراغ السجل بالكامل</button>
        </form>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-history" style="color:var(--gold); margin-left:8px;"></i> قائمة الإجراءات الأخيرة</h2>
        </div>
        
        @if($logs->isEmpty())
            <div class="empty">
                <i class="fas fa-box-open"></i>
                السجل فارغ حالياً
            </div>
        @else
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المشرف</th>
                        <th>الإجراء</th>
                        <th>القسم</th>
                        <th>التفاصيل</th>
                        <th>التاريخ والوقت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem">{{ $log->id }}</td>
                        <td style="font-weight:600; color:var(--gold);">
                            <i class="fas fa-user-shield" style="font-size:0.8rem; margin-left:4px;"></i> {{ $log->admin_name }}
                        </td>
                        <td>
                            @if($log->action == 'created') <span class="badge badge-green">أضاف</span>
                            @elseif($log->action == 'updated') <span class="badge" style="background:rgba(59,130,246,0.12);color:#93c5fd;">عدل</span>
                            @elseif($log->action == 'deleted') <span class="badge" style="background:rgba(239,68,68,0.12);color:#fca5a5;">حذف</span>
                            @elseif($log->action == 'approved') <span class="badge" style="background:rgba(212,175,55,0.12);color:var(--gold);">وافق على</span>
                            @else <span class="badge badge-gray">{{ $log->action }}</span> @endif
                        </td>
                        <td>
                            @if($log->model_type == 'member') أعضاء القبيلة
                            @elseif($log->model_type == 'activity') الأنشطة
                            @elseif($log->model_type == 'news') الأخبار
                            @endif
                        </td>
                        <td style="color:var(--text-muted); font-size:0.85rem;">
                            @php $details = json_decode($log->details, true); @endphp
                            @if($details && isset($details['title'])) {{ Str::limit($details['title'], 40) }}
                            @elseif($details && isset($details['name'])) {{ Str::limit($details['name'], 40) }}
                            @else — @endif
                        </td>
                        <td style="font-size:0.8rem; color:var(--text-muted); direction:ltr;">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <div style="margin-top: 1rem;">
            {{ $logs->links('pagination::default') }}
        </div>
    </div>
</div>
@endsection
