<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') | إدارة قبيلة مسونق</title>

    @php
        $settings = \Illuminate\Support\Facades\DB::table('tribe_settings')->first();
    @endphp
    @if(isset($settings) && $settings->logo)
        <link rel="icon" type="image/png" href="{{ Storage::url($settings->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold:#C9A84C; --gold-light:#E8C97A; --gold-dark:#9A7A2E;
            --dark:#0D1117; --dark2:#161B22; --dark3:#1C2128;
            --text:#E6EDF3; --text-muted:#8B949E; --border:rgba(201,168,76,0.2);
            --green:#22c55e; --red:#ef4444; --blue:#3b82f6;
            --sidebar-w:260px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Cairo',sans-serif; background:var(--dark); color:var(--text); min-height:100vh; display:flex; }

        /* SIDEBAR */
        .sidebar {
            width:var(--sidebar-w); background:var(--dark2);
            border-left:1px solid var(--border);
            position:fixed; top:0; right:0; bottom:0;
            display:flex; flex-direction:column; overflow-y:auto; z-index:100;
        }
        .sidebar-head {
            padding:1.5rem; border-bottom:1px solid var(--border);
            display:flex; align-items:center; gap:0.8rem;
        }
        .sidebar-head .icon {
            width:40px; height:40px; border-radius:10px;
            background:linear-gradient(135deg,var(--gold),var(--gold-dark));
            display:flex;align-items:center;justify-content:center;
            font-size:1.2rem;color:var(--dark);flex-shrink:0;
        }
        .sidebar-head .lbl { font-size:0.9rem; font-weight:700; color:var(--gold); line-height:1.3; }
        .sidebar-head .sub { font-size:0.72rem; color:var(--text-muted); }

        .nav-section { padding:1rem 0.75rem 0.3rem; font-size:0.7rem; font-weight:700; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase; }
        .sidebar nav a {
            display:flex; align-items:center; gap:0.8rem;
            padding:0.7rem 1rem; margin:0.15rem 0.5rem;
            border-radius:10px; text-decoration:none;
            color:var(--text-muted); font-size:0.88rem; font-weight:600;
            transition:all 0.2s;
        }
        .sidebar nav a i { width:18px; text-align:center; font-size:0.95rem; }
        .sidebar nav a:hover { background:rgba(201,168,76,0.08); color:var(--text); }
        .sidebar nav a.active { background:rgba(201,168,76,0.15); color:var(--gold); }
        .sidebar-foot {
            margin-top:auto; padding:1rem; border-top:1px solid var(--border);
        }
        .sidebar-foot .admin-info { margin-bottom:0.8rem; font-size:0.82rem; color:var(--text-muted); }
        .sidebar-foot .admin-info span { color:var(--text); font-weight:600; display:block; }
        .btn-logout {
            width:100%; background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.25);
            color:#fca5a5; border-radius:8px; padding:0.55rem;
            font-family:'Cairo',sans-serif; font-size:0.85rem; font-weight:600;
            cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem;
        }
        .btn-logout:hover { background:rgba(239,68,68,0.22); }

        /* MAIN */
        .main { margin-right:var(--sidebar-w); flex:1; min-height:100vh; display:flex; flex-direction:column; }
        .topbar {
            background:var(--dark2); border-bottom:1px solid var(--border);
            padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:50;
        }
        .topbar h1 { font-size:1.2rem; font-weight:700; }
        .topbar .breadcrumb { font-size:0.8rem; color:var(--text-muted); margin-top:0.15rem; }
        .topbar-actions { display:flex; gap:0.7rem; }

        .content { padding:2rem; flex:1; }

        /* ALERTS */
        .alert {
            padding:0.9rem 1.2rem; border-radius:10px;
            font-size:0.88rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:0.6rem;
        }
        .alert-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); color:#86efac; }
        .alert-error   { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); color:#fca5a5; }

        /* CARDS */
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1.2rem; margin-bottom:2rem; }
        .stat-card {
            background:var(--dark2); border:1px solid var(--border); border-radius:14px;
            padding:1.4rem; display:flex; align-items:center; gap:1rem;
        }
        .stat-icon {
            width:48px;height:48px;border-radius:12px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;font-size:1.3rem;
        }
        .stat-num { font-size:2rem; font-weight:900; line-height:1; }
        .stat-lbl { font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem; }

        /* TABLE */
        .table-wrap { background:var(--dark2); border:1px solid var(--border); border-radius:14px; overflow:hidden; }
        .table-head { padding:1.2rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.8rem; }
        .table-head h2 { font-size:1rem; font-weight:700; }
        table { width:100%; border-collapse:collapse; }
        th { padding:0.8rem 1.2rem; background:var(--dark3); font-size:0.78rem; font-weight:700; color:var(--text-muted); text-align:right; letter-spacing:0.5px; white-space:nowrap; }
        td { padding:1rem 1.2rem; border-top:1px solid rgba(201,168,76,0.08); font-size:0.88rem; vertical-align:middle; }
        tr:hover td { background:rgba(201,168,76,0.03); }
        .td-img { width:50px; height:50px; border-radius:8px; object-fit:cover; background:var(--dark3); display:flex;align-items:center;justify-content:center;color:var(--text-muted); }
        .td-img img { width:100%;height:100%;object-fit:cover;border-radius:8px; }
        .badge { display:inline-block;padding:0.2rem 0.7rem;border-radius:20px;font-size:0.75rem;font-weight:600; }
        .badge-green { background:rgba(34,197,94,0.12);color:var(--green); }
        .badge-gray  { background:rgba(139,148,158,0.12);color:var(--text-muted); }

        /* BUTTONS */
        .btn {
            display:inline-flex;align-items:center;gap:0.4rem;
            padding:0.5rem 1rem;border-radius:8px;font-family:'Cairo',sans-serif;
            font-size:0.82rem;font-weight:600;cursor:pointer;transition:all 0.2s;
            text-decoration:none;border:none;
        }
        .btn-primary { background:linear-gradient(135deg,var(--gold),var(--gold-dark));color:var(--dark); }
        .btn-primary:hover { transform:translateY(-1px);box-shadow:0 6px 15px rgba(201,168,76,0.3); }
        .btn-edit  { background:rgba(59,130,246,0.12);color:#93c5fd;border:1px solid rgba(59,130,246,0.25); }
        .btn-edit:hover { background:rgba(59,130,246,0.22); }
        .btn-del   { background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.25); }
        .btn-del:hover { background:rgba(239,68,68,0.2); }

        /* FORM */
        .form-card { background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2rem; }
        .form-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem; }
        .form-group { margin-bottom:0; }
        .form-group.full { grid-column:1/-1; }
        label.lbl { display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);margin-bottom:0.45rem; }
        input[type=text],input[type=email],input[type=date],input[type=tel],input[type=password],select,textarea {
            width:100%;background:var(--dark3);border:1px solid var(--border);
            border-radius:10px;padding:0.7rem 1rem;
            color:var(--text);font-family:'Cairo',sans-serif;font-size:0.9rem;
            transition:border-color 0.2s;outline:none;
        }
        input:focus,select:focus,textarea:focus { border-color:var(--gold); }
        textarea { resize:vertical;min-height:120px; }
        .field-error { color:#fca5a5;font-size:0.78rem;margin-top:0.3rem; }
        .toggle-wrap { display:flex;align-items:center;gap:0.8rem;padding:0.7rem 0; }
        .toggle-wrap input[type=checkbox] { width:18px;height:18px;accent-color:var(--gold);cursor:pointer; }
        .toggle-wrap label { cursor:pointer;font-size:0.9rem;margin:0; }

        .img-preview { width:100px;height:100px;border-radius:10px;object-fit:cover;border:1px solid var(--border);margin-top:0.5rem; }

        /* EMPTY STATE */
        .empty { text-align:center;padding:4rem;color:var(--text-muted); }
        .empty i { font-size:3rem;display:block;margin-bottom:1rem;color:var(--border); }

        /* MOBILE */
        @media(max-width:900px){
            .sidebar { transform:translateX(100%); transition:transform 0.3s; }
            .sidebar.open { transform:translateX(0); }
            .main { margin-right:0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-head">
        <div class="icon">⚜</div>
        <div>
            <div class="lbl">قبيلة مسونق</div>
            <div class="sub">لوحة الإدارة</div>
        </div>
    </div>

    @php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    @endphp

    <div class="nav-section">القائمة الرئيسية</div>
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> لوحة التحكم
        </a>
        @if($isSuper || in_array('manage_members', $perms))
        <a href="{{ route('admin.members') }}" class="{{ request()->routeIs('admin.members*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> الأعضاء
        </a>
        @endif
        @if($isSuper || in_array('manage_activities', $perms))
        <a href="{{ route('admin.activities') }}" class="{{ request()->routeIs('admin.activities*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> الأنشطة
        </a>
        @endif
        @if($isSuper || in_array('manage_news', $perms))
        <a href="{{ route('admin.news') }}" class="{{ request()->routeIs('admin.news*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> الأخبار
        </a>
        @endif
    </nav>

    @if($loggedAdmin && $loggedAdmin->role === 'super_admin')
    <div class="nav-section">إدارة المستخدمين</div>
    <nav>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i> مدراء النظام
        </a>
        <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> سجل النشاطات
        </a>
    </nav>

    <div class="nav-section">الإعدادات</div>
    <nav>
        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> إعدادات القبيلة
        </a>
    </nav>
    @endif
    
    <div class="nav-section">اختصارات</div>
    <nav>
        <a href="{{ route('home') }}" target="_blank">
            <i class="fas fa-external-link-alt"></i> عرض الموقع
        </a>
    </nav>

    <div class="sidebar-foot">
        <div class="admin-info">
            مرحباً،
            <span>{{ session('admin_name', 'المدير') }}</span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <h1>@yield('page-title', 'لوحة التحكم')</h1>
            <div class="breadcrumb">@yield('breadcrumb', 'الرئيسية')</div>
        </div>
        <div class="topbar-actions">
            @yield('topbar-actions')
        </div>
    </div>

    <div class="content">
        @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
