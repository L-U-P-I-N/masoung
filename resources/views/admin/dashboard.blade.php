@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'نظرة عامة')
@section('breadcrumb', 'الرئيسية / لوحة التحكم')

@section('content')

<style>
    /* Dashboard Specific Styles */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Stats Grid */
    .premium-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .p-stat-card {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .p-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(201, 168, 76, 0.15);
        border-color: var(--gold);
    }

    .p-stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle at top right, rgba(201, 168, 76, 0.08), transparent);
        border-radius: 0 1.25rem 0 100%;
        pointer-events: none;
    }

    .p-stat-icon {
        width: 58px;
        height: 58px;
        border-radius: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: inset 0 0 15px rgba(255, 255, 255, 0.05);
    }

    .p-stat-card:hover .p-stat-icon {
        transform: scale(1.1) rotate(-5deg);
        filter: brightness(1.2);
    }

    .p-stat-info {
        flex-grow: 1;
        z-index: 2;
    }

    .p-stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 0.35rem;
        letter-spacing: -0.5px;
    }

    .p-stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Urgent Status */
    .card-urgent {
        border-color: rgba(239, 68, 68, 0.4);
        background: linear-gradient(145deg, var(--dark2) 0%, rgba(239, 68, 68, 0.03) 100%);
    }
    .card-urgent .p-stat-icon { 
        background: rgba(239, 68, 68, 0.1) !important; 
        color: #ff5f5f !important;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.15);
    }
    .card-urgent .p-stat-value { color: #fca5a5; }

    /* Quick Actions */
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }

    .action-tile {
        background: var(--dark2);
        border: 1px solid var(--border);
        border-radius: 1.15rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        text-decoration: none;
        color: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .action-tile:hover {
        background: var(--dark3);
        border-color: var(--gold);
        transform: translateX(-8px);
        box-shadow: -10px 10px 20px rgba(0, 0, 0, 0.3);
    }

    .action-tile i {
        width: 40px;
        height: 40px;
        background: rgba(201, 168, 76, 0.1);
        color: var(--gold);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Content Sections */
    .data-sections {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .section-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        padding: 1.5rem;
        height: 100%;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .section-header h2 {
        font-size: 1.15rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Feed Card Style (Desktop/Mobile) */
    .feed-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-radius: 0.75rem;
        transition: background 0.2s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .feed-item:last-child { border-bottom: none; }
    .feed-item:hover { background: rgba(255, 255, 255, 0.02); }

    .feed-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .feed-title {
        font-weight: 500;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .feed-meta {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Responsive Queries */
    @media (max-width: 1200px) {
        .premium-stats { grid-template-columns: repeat(2, 1fr); }
        .data-sections { grid-template-columns: 1fr; }
    }

    @media (max-width: 600px) {
        .premium-stats { grid-template-columns: 1fr; }
        .action-grid { grid-template-columns: 1fr; }
        .p-stat-card { padding: 1.25rem; }
        .section-card { padding: 1rem; }
    }
</style>

<div class="dashboard-container">
    
    {{-- Stats Grid --}}
    <div class="premium-stats">
        {{-- Members --}}
        <div class="p-stat-card">
            <div class="p-stat-icon" style="background: rgba(201, 168, 76, 0.1); color: var(--gold);">
                <i class="fas fa-users"></i>
            </div>
            <div class="p-stat-info">
                <div class="p-stat-value">{{ number_format($stats['members']) }}</div>
                <div class="p-stat-label">عضو نشط</div>
            </div>
        </div>

        {{-- Pending --}}
        @if($stats['pending_members'] > 0)
        <a href="{{ route('admin.members') }}?status=pending" class="p-stat-card card-urgent" style="text-decoration:none">
            <div class="p-stat-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="p-stat-info">
                <div class="p-stat-value">{{ number_format($stats['pending_members']) }}</div>
                <div class="p-stat-label">ينتظر الموافقة</div>
            </div>
            <i class="fas fa-chevron-left" style="position:absolute; left: 1rem; top: 50%; transform: translateY(-50%); opacity: 0.3; font-size: 0.8rem;"></i>
        </a>
        @endif

        {{-- News --}}
        <div class="p-stat-card">
            <div class="p-stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #60a5fa;">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="p-stat-info">
                <div class="p-stat-value">{{ number_format($stats['news']) }}</div>
                <div class="p-stat-label">خبر منشور</div>
            </div>
        </div>

        {{-- Activities --}}
        <div class="p-stat-card">
            <div class="p-stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #4ade80;">
                <i class="fas fa-calendar-star"></i>
            </div>
            <div class="p-stat-info">
                <div class="p-stat-value">{{ number_format($stats['activities']) }}</div>
                <div class="p-stat-label">نشاط وفاعلية</div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    @php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    @endphp
    
    <div class="action-grid">
        @if($isSuper || in_array('manage_members', $perms))
        <a href="{{ route('admin.members.create') }}" class="action-tile">
            <i class="fas fa-user-plus"></i>
            <span>إضافة عضو جديد</span>
        </a>
        @endif
        
        @if($isSuper || in_array('manage_news', $perms))
        <a href="{{ route('admin.news.create') }}" class="action-tile">
            <i class="fas fa-file-signature"></i>
            <span>نشر خبر جديد</span>
        </a>
        @endif
        
        @if($isSuper || in_array('manage_activities', $perms))
        <a href="{{ route('admin.activities.create') }}" class="action-tile">
            <i class="fas fa-calendar-plus"></i>
            <span>إضافة نشاط</span>
        </a>
        @endif
    </div>

    {{-- Data Sections --}}
    <div class="data-sections">
        
        {{-- Latest News --}}
        <div class="section-card">
            <div class="section-header">
                <h2><i class="fas fa-newspaper" style="color:var(--gold)"></i>أحدث الأخبار</h2>
                <a href="{{ route('admin.news') }}" class="btn-text" style="color:var(--gold); text-decoration:none; font-size:0.85rem">عرض الكل</a>
            </div>
            
            <div class="feed-list">
                @forelse($latestNews as $n)
                <div class="feed-item">
                    <div class="feed-content">
                        <div class="feed-title">{{ Str::limit($n->title, 50) }}</div>
                        <div class="feed-meta">
                            <span><i class="far fa-clock" style="margin-left:3px"></i> {{ \Carbon\Carbon::parse($n->created_at)->format('d/m/Y') }}</span>
                            <span class="badge {{ $n->is_published ? 'badge-green' : 'badge-gray' }}" style="padding: 2px 8px; font-size: 0.65rem">
                                {{ $n->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.news.edit', $n->id) }}" class="btn-icon" style="color:var(--text-muted)"><i class="fas fa-edit"></i></a>
                </div>
                @empty
                <div class="empty-state" style="text-align:center; padding: 2rem; color:var(--text-muted)">
                    <i class="fas fa-newspaper" style="font-size: 2rem; display:block; margin-bottom: 0.5rem; opacity:0.3"></i>
                    لا توجد أخبار حالياً
                </div>
                @endforelse
            </div>
        </div>

        {{-- Latest Activities --}}
        <div class="section-card">
            <div class="section-header">
                <h2><i class="fas fa-sparkles" style="color:var(--gold)"></i>الأنشطة القادمة</h2>
                <a href="{{ route('admin.activities') }}" class="btn-text" style="color:var(--gold); text-decoration:none; font-size:0.85rem">عرض الكل</a>
            </div>
            
            <div class="feed-list">
                @forelse($latestActs as $a)
                <div class="feed-item">
                    <div class="feed-content">
                        <div class="feed-title">{{ Str::limit($a->title, 45) }}</div>
                        <div class="feed-meta">
                            <span style="direction:ltr"><i class="far fa-calendar-alt" style="margin-left:3px"></i> {{ \Carbon\Carbon::parse($a->activity_date)->format('Y/m/d') }}</span>
                            <span class="badge {{ $a->is_published ? 'badge-green' : 'badge-gray' }}" style="padding: 2px 8px; font-size: 0.65rem">
                                {{ $a->is_published ? 'نشط' : 'متوقف' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.activities.edit', $a->id) }}" class="btn-icon" style="color:var(--text-muted)"><i class="fas fa-edit"></i></a>
                </div>
                @empty
                <div class="empty-state" style="text-align:center; padding: 2rem; color:var(--text-muted)">
                    <i class="fas fa-calendar-alt" style="font-size: 2rem; display:block; margin-bottom: 0.5rem; opacity:0.3"></i>
                    لا توجد أنشطة قادمة
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection
