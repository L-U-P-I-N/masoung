<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'قبيلة مسونق'); ?> | <?php echo e($settings->tribe_name ?? 'قبيلة مسونق'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'الموقع الرسمي لقبيلة مسونق'); ?>">
    
    <?php if(isset($settings) && $settings->logo): ?>
        <link rel="icon" type="image/png" href="<?php echo e(Storage::url($settings->logo)); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold:       #C9A84C;
            --gold-light: #E8C97A;
            --gold-dark:  #9A7A2E;
            --dark:       #0D1117;
            --dark2:      #161B22;
            --dark3:      #1C2128;
            --text:       #E6EDF3;
            --text-muted: #8B949E;
            --border:     rgba(201,168,76,0.25);
        }
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--dark);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        nav {
            position: fixed; top:0; right:0; left:0; z-index:1000;
            background: rgba(13,17,23,0.92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
        }
        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 70px;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 0.8rem;
            text-decoration: none;
            font-family: 'Amiri', serif;
            font-size: 1.3rem; 
            font-weight: 700;
            color: var(--gold);
            cursor: pointer;
        }
        .nav-brand .brand-icon {
            width:42px; height:42px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 10px;
            display: flex; align-items:center; justify-content:center;
            font-size: 1.3rem; color: var(--dark);
        }
        .nav-brand .brand-text {
            font-family: 'Amiri', serif;
            font-size: 1.3rem; font-weight: 700;
            color: var(--gold);
        }
        .nav-links { display:flex; gap:0.3rem; list-style:none; }
        .nav-links a {
            color: var(--text-muted); text-decoration:none;
            padding: 0.5rem 1rem; border-radius:8px;
            font-size: 0.9rem; font-weight:600;
            transition: all 0.2s;
        }
        .nav-links a:hover, .nav-links a.active {
            background: rgba(201,168,76,0.12);
            color: var(--gold);
        }
        .nav-toggle {
            display:none; background:none; border:none;
            color:var(--text); font-size:1.4rem; cursor:pointer;
        }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            background: var(--dark);
            position: relative; overflow:hidden;
            display: flex; align-items:center; justify-content:center;
            padding-top: 70px;
        }
        .hero-bg {
            position:absolute; inset:0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(201,168,76,0.1) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(201,168,76,0.06) 0%, transparent 60%);
        }
        .hero-pattern {
            position:absolute; inset:0; opacity:0.04;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C9A84C' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-content {
            position:relative; text-align:center;
            padding: 2rem;
        }
        .hero-badge {
            display:inline-block;
            border: 1px solid var(--border);
            background: rgba(201,168,76,0.08);
            color: var(--gold-light);
            padding: 0.4rem 1.2rem;
            border-radius: 50px; font-size:0.85rem;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        .hero-title {
            font-family: 'Amiri', serif;
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight:700; line-height:1.1;
            background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold) 50%, var(--gold-dark) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color:transparent;
            margin-bottom:1rem;
        }
        .hero-sub {
            font-size: clamp(1rem, 2vw, 1.25rem);
            color: var(--text-muted); max-width:600px; margin:0 auto 2.5rem;
            line-height:1.8;
        }
        .hero-stats {
            display:flex; gap:3rem; justify-content:center;
            flex-wrap:wrap; margin-bottom:2.5rem;
        }
        .hero-stat { text-align:center; }
        .hero-stat .num {
            font-size:2.5rem; font-weight:900;
            color: var(--gold); line-height:1;
        }
        .hero-stat .lbl { font-size:0.85rem; color:var(--text-muted); margin-top:0.3rem; }
        .hero-cta {
            display:inline-flex; align-items:center; gap:0.7rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--dark); font-weight:700; font-size:1rem;
            padding: 0.85rem 2.5rem; border-radius:50px;
            text-decoration:none; transition:all 0.3s;
        }
        .hero-cta:hover { transform:translateY(-2px); box-shadow:0 12px 30px rgba(201,168,76,0.35); }

        /* ===== SECTIONS ===== */
        .section { padding: 5rem 2rem; }
        .section-inner { max-width:1280px; margin:0 auto; }
        .section-header { text-align:center; margin-bottom:3rem; }
        .section-label {
            display:inline-block; color:var(--gold);
            font-size:0.8rem; font-weight:700; letter-spacing:3px;
            text-transform:uppercase; margin-bottom:0.8rem;
        }
        .section-title {
            font-family:'Amiri',serif; font-size:clamp(1.8rem,4vw,2.8rem);
            color: var(--text); margin-bottom:1rem;
        }
        .section-line {
            width:60px; height:3px;
            background: linear-gradient(90deg, var(--gold), transparent);
            margin:0 auto;
        }

        /* ===== CARDS ===== */
        .cards-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(320px,1fr)); gap:1.5rem; }
        .card {
            background: var(--dark2);
            border: 1px solid var(--border);
            border-radius:16px; overflow:hidden;
            transition: all 0.3s;
        }
        .card:hover { transform:translateY(-5px); border-color: rgba(201,168,76,0.5); box-shadow:0 20px 40px rgba(0,0,0,0.4); }
        .card-img { width:100%; height:200px; object-fit:cover; background:var(--dark3); display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:3rem; }
        .card-img img { width:100%; height:100%; object-fit:cover; }
        .card-body { padding:1.5rem; }
        .card-date { font-size:0.8rem; color:var(--gold); margin-bottom:0.6rem; }
        .card-title { font-size:1.1rem; font-weight:700; margin-bottom:0.7rem; line-height:1.5; }
        .card-desc { font-size:0.9rem; color:var(--text-muted); line-height:1.7; margin-bottom:1rem; }
        .card-link {
            display:inline-flex; align-items:center; gap:0.4rem;
            color:var(--gold); font-size:0.85rem; font-weight:600;
            text-decoration:none; transition:gap 0.2s;
        }
        .card-link:hover { gap:0.8rem; }

        /* ===== FOOTER ===== */
        footer {
            background: var(--dark2);
            border-top: 1px solid var(--border);
            padding: 3rem 2rem 1.5rem;
            text-align:center;
        }
        .footer-inner { max-width:800px; margin:0 auto; }
        .footer-logo { font-family:'Amiri',serif; font-size:2rem; color:var(--gold); margin-bottom:1rem; }
        .footer-desc { color:var(--text-muted); font-size:0.9rem; line-height:1.8; margin-bottom:2rem; }
        .footer-links { display:flex; gap:1.5rem; justify-content:center; flex-wrap:wrap; margin-bottom:2rem; }
        .footer-links a { color:var(--text-muted); text-decoration:none; font-size:0.85rem; transition:color 0.2s; }
        .footer-links a:hover { color:var(--gold); }
        .footer-copy { color:var(--text-muted); font-size:0.8rem; border-top:1px solid var(--border); padding-top:1.5rem; margin-top:1rem; }

        /* ===== MOBILE NAV ===== */
        .mobile-menu {
            display:none; position:fixed; inset:0; z-index:999;
            background: rgba(13,17,23,0.98);
            flex-direction:column; align-items:center; justify-content:center; gap:1.5rem;
        }
        .mobile-menu.open { display:flex; }
        .mobile-menu a {
            font-family:'Amiri',serif; font-size:1.8rem;
            color:var(--text); text-decoration:none;
            transition:color 0.2s;
        }
        .mobile-menu a:hover { color:var(--gold); }
        .mobile-close {
            position:absolute; top:1.5rem; left:1.5rem;
            background:none; border:none; color:var(--text-muted);
            font-size:1.5rem; cursor:pointer;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            padding: 120px 2rem 60px;
            background: linear-gradient(180deg, rgba(201,168,76,0.06) 0%, transparent 100%);
            border-bottom: 1px solid var(--border);
            text-align:center;
        }
        .page-header h1 { font-family:'Amiri',serif; font-size:clamp(2rem,5vw,3.5rem); color:var(--gold); }
        .breadcrumb { margin-top:0.8rem; color:var(--text-muted); font-size:0.85rem; }
        .breadcrumb a { color:var(--text-muted); text-decoration:none; }
        .breadcrumb a:hover { color:var(--gold); }

        @media(max-width:768px){
            .nav-links { display:none; }
            .nav-toggle { display:block; }
            .hero-stats { gap:1.5rem; }
        }

        /* ===== BANNER SLIDER ===== */
        .banner-container {
            width: 100%; position: relative;
            background: var(--dark3);
            border-radius: 20px; overflow: hidden;
            border: 1px solid var(--border);
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .banner-slider {
            display: flex; overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none; -ms-overflow-style: none;
            cursor: grab;
        }
        .banner-slider::-webkit-scrollbar { display: none; }
        .banner-item {
            flex: 0 0 100%; scroll-snap-align: start;
            position: relative; height: 500px;
        }
        .banner-item img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .banner-slider:active { cursor: grabbing; }
        
        .banner-nav {
            position: absolute; top: 0; bottom: 0; width: 60px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(to right, rgba(13,17,23,0.5), transparent);
            color: white; font-size: 1.5rem; cursor: pointer;
            z-index: 10; opacity: 0; transition: opacity 0.3s;
        }
        .banner-nav.next {
            right: 0; left: auto;
            background: linear-gradient(to left, rgba(13,17,23,0.5), transparent);
        }
        .banner-container:hover .banner-nav { opacity: 1; }
        .banner-nav:hover { color: var(--gold); }

        .banner-dots {
            position: absolute; bottom: 1.5rem; left: 0; right: 0;
            display: flex; justify-content: center; gap: 0.6rem; z-index: 10;
        }
        .banner-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,0.3); cursor: pointer;
            transition: all 0.3s;
        }
        .banner-dot.active { background: var(--gold); width: 24px; border-radius: 10px; }

        @media(max-width: 768px) {
            .banner-item { height: 300px; }
            .banner-nav { display: none; }
        }

        /* ===== HOME HERO BANNER ===== */
        .hero-banner {
            position: relative; height: 100vh; min-height: 700px;
            background: var(--dark); overflow: hidden;
        }
        .hero-banner .banner-item { height: 100vh; min-height: 700px; }
        .hero-banner .banner-item::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(0deg, var(--dark) 0%, rgba(13,17,23,0.3) 50%, rgba(13,17,23,0.1) 100%);
        }
        .hero-banner-content {
            position: absolute; inset: 0; z-index: 5;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; padding: 2rem; pointer-events: none;
        }
        .hero-banner-content > * { pointer-events: auto; }
        .banner-label {
            display: inline-block; padding: 0.4rem 1rem; border-radius: 50px;
            background: rgba(201,168,76,0.15); border: 1px solid var(--gold);
            color: var(--gold-light); font-size: 0.8rem; font-weight: 700;
            margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 2px;
        }
        .banner-title {
            font-family: 'Amiri', serif; font-size: clamp(2.5rem, 6vw, 4.5rem);
            color: white; line-height: 1.2; margin-bottom: 2rem;
            text-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .banner-btn {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--dark); padding: 0.8rem 2.2rem; border-radius: 50px;
            font-weight: 700; text-decoration: none; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 0.7rem;
        }
        .banner-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(201,168,76,0.4); }

        .hero-stats-overlay {
            position: absolute; bottom: 3rem; left: 0; right: 0; z-index: 10;
            max-width: 1200px; margin: 0 auto;
            display: flex; justify-content: center; gap: 4rem; flex-wrap: wrap;
            padding: 0 2rem;
        }
        .stat-item { text-align: center; }
        .stat-item .val { font-size: 2.2rem; font-weight: 900; color: var(--gold); line-height: 1; }
        .stat-item .lab { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.4rem; text-transform: uppercase; letter-spacing: 1px; }

        @media(max-width: 768px) {
            .hero-banner, .hero-banner .banner-item { height: 80vh; min-height: 500px; }
            .banner-title { font-size: 2.2rem; }
            .hero-stats-overlay { bottom: 2rem; gap: 1.5rem; }
            .stat-item .val { font-size: 1.5rem; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<nav>
    <div class="nav-inner">
        <a href="<?php echo e(route('home')); ?>" class="nav-brand">⚜ <?php echo e($settings->tribe_name ?? 'قبيلة مسونق'); ?> ⚜</a>
        <ul class="nav-links">
            <li><a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">الرئيسية</a></li>
            <li><a href="<?php echo e(route('about')); ?>" class="<?php echo e(request()->routeIs('about') ? 'active' : ''); ?>">عن القبيلة</a></li>
            <li><a href="<?php echo e(route('members')); ?>" class="<?php echo e(request()->routeIs('members') ? 'active' : ''); ?>">الأعضاء</a></li>
            <li><a href="<?php echo e(route('activities')); ?>" class="<?php echo e(request()->routeIs('activities*') ? 'active' : ''); ?>">الأنشطة</a></li>
            <li><a href="<?php echo e(route('news')); ?>" class="<?php echo e(request()->routeIs('news*') ? 'active' : ''); ?>">الأخبار</a></li>
        </ul>
        <button class="nav-toggle" onclick="document.getElementById('mobileMenu').classList.add('open')">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <button class="mobile-close" onclick="document.getElementById('mobileMenu').classList.remove('open')">
        <i class="fas fa-times"></i>
    </button>
    <a href="<?php echo e(route('home')); ?>" onclick="document.getElementById('mobileMenu').classList.remove('open')">الرئيسية</a>
    <a href="<?php echo e(route('about')); ?>" onclick="document.getElementById('mobileMenu').classList.remove('open')">عن القبيلة</a>
    <a href="<?php echo e(route('members')); ?>" onclick="document.getElementById('mobileMenu').classList.remove('open')">الأعضاء</a>
    <a href="<?php echo e(route('activities')); ?>" onclick="document.getElementById('mobileMenu').classList.remove('open')">الأنشطة</a>
    <a href="<?php echo e(route('news')); ?>" onclick="document.getElementById('mobileMenu').classList.remove('open')">الأخبار</a>
</div>

<!-- Global Success Alert -->
<?php if(session('success')): ?>
<div style="position:fixed; top:85px; right:20px; left:20px; z-index:2000; max-width:600px; margin:0 auto;">
    <div style="background:rgba(34,197,94,0.15); border:1px solid rgba(34,197,94,0.4); backdrop-filter:blur(10px); color:#86efac; padding:1rem 1.5rem; border-radius:15px; display:flex; align-items:center; gap:0.8rem; box-shadow:0 10px 30px rgba(0,0,0,0.3); animation: slideIn 0.4s ease-out;">
        <i class="fas fa-check-circle" style="font-size:1.2rem"></i>
        <div style="flex:1; font-size:0.95rem; font-weight:600;"><?php echo e(session('success')); ?></div>
        <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; color:#86efac; cursor:pointer; font-size:1.1rem; padding:0.2rem;">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<style>
    @keyframes slideIn { from { transform:translateY(-20px); opacity:0; } to { transform:translateY(0); opacity:1; } }
</style>
<?php endif; ?>

<?php echo $__env->yieldContent('content'); ?>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">⚜ <?php echo e($settings->tribe_name ?? 'قبيلة مسونق'); ?> ⚜</div>
        <p class="footer-desc"><?php echo e($settings->tribe_description ?? 'الموقع الرسمي لقبيلة مسونق'); ?></p>
        <div class="footer-links">
            <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
            <a href="<?php echo e(route('about')); ?>">عن القبيلة</a>
            <a href="<?php echo e(route('members')); ?>">الأعضاء</a>
            <a href="<?php echo e(route('activities')); ?>">الأنشطة</a>
            <a href="<?php echo e(route('news')); ?>">الأخبار</a>
        </div>
        <?php if($settings->contact_phone ?? false): ?>
        <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:0.5rem;">
            <i class="fas fa-phone" style="color:var(--gold);margin-left:0.4rem"></i>
            <?php echo e($settings->contact_phone); ?>

        </p>
        <?php endif; ?>
        <div class="footer-copy">
            جميع الحقوق محفوظة &copy; <?php echo e(date('Y')); ?> — <?php echo e($settings->tribe_name ?? 'قبيلة مسونق'); ?>

        </div>
    </div>
</footer>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/layouts/public.blade.php ENDPATH**/ ?>