<?php $__env->startSection('title', 'لوحة التحكم'); ?>
<?php $__env->startSection('page-title', 'لوحة التحكم'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / لوحة التحكم'); ?>

<?php $__env->startSection('content'); ?>


<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(201,168,76,0.15);color:var(--gold)"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-num"><?php echo e($stats['members']); ?></div>
            <div class="stat-lbl">الأعضاء</div>
        </div>
    </div>
    <?php if($stats['pending_members'] > 0): ?>
    <div class="stat-card" style="border: 1px solid rgba(239,68,68,0.3); background: rgba(239,68,68,0.05);">
        <div class="stat-icon" style="background:rgba(239,68,68,0.15);color:#fca5a5"><i class="fas fa-user-clock"></i></div>
        <div>
            <div class="stat-num"><?php echo e($stats['pending_members']); ?></div>
            <div class="stat-lbl" style="color:#fca5a5">بانتظار الموافقة</div>
        </div>
    </div>
    <?php endif; ?>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(59,130,246,0.15);color:#93c5fd"><i class="fas fa-newspaper"></i></div>
        <div>
            <div class="stat-num"><?php echo e($stats['news']); ?></div>
            <div class="stat-lbl">الأخبار</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:var(--green)"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-num"><?php echo e($stats['activities']); ?></div>
            <div class="stat-lbl">الأنشطة</div>
        </div>
    </div>
</div>


<?php
    $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
    $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
    $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
?>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:2rem">
    <?php if($isSuper || in_array('manage_members', $perms)): ?>
    <a href="<?php echo e(route('admin.members.create')); ?>" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-user-plus"></i> إضافة عضو
    </a>
    <?php endif; ?>
    
    <?php if($isSuper || in_array('manage_activities', $perms)): ?>
    <a href="<?php echo e(route('admin.activities.create')); ?>" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-plus-circle"></i> إضافة نشاط
    </a>
    <?php endif; ?>
    
    <?php if($isSuper || in_array('manage_news', $perms)): ?>
    <a href="<?php echo e(route('admin.news.create')); ?>" class="btn btn-primary" style="justify-content:center;padding:0.8rem">
        <i class="fas fa-edit"></i> إضافة خبر
    </a>
    <?php endif; ?>
</div>


<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

    
    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-newspaper" style="color:var(--gold);margin-left:0.5rem"></i>آخر الأخبار</h2>
            <a href="<?php echo e(route('admin.news')); ?>" class="btn btn-edit">عرض الكل</a>
        </div>
        <?php if($latestNews->isEmpty()): ?>
        <div class="empty"><i class="fas fa-newspaper"></i>لا توجد أخبار</div>
        <?php else: ?>
        <table>
            <thead><tr><th>العنوان</th><th>الحالة</th><th>التاريخ</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $latestNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(Str::limit($n->title, 35)); ?></td>
                    <td>
                        <span class="badge <?php echo e($n->is_published ? 'badge-green' : 'badge-gray'); ?>">
                            <?php echo e($n->is_published ? 'منشور' : 'مسودة'); ?>

                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:0.8rem"><?php echo e(\Carbon\Carbon::parse($n->created_at)->format('d/m/Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    
    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-calendar-alt" style="color:var(--gold);margin-left:0.5rem"></i>آخر الأنشطة</h2>
            <a href="<?php echo e(route('admin.activities')); ?>" class="btn btn-edit">عرض الكل</a>
        </div>
        <?php if($latestActs->isEmpty()): ?>
        <div class="empty"><i class="fas fa-calendar-alt"></i>لا توجد أنشطة</div>
        <?php else: ?>
        <table>
            <thead><tr><th>النشاط</th><th>التاريخ</th><th>الحالة</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $latestActs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(Str::limit($a->title, 30)); ?></td>
                    <td style="color:var(--text-muted);font-size:0.8rem;direction:ltr"><?php echo e(\Carbon\Carbon::parse($a->activity_date)->format('d/m/Y')); ?></td>
                    <td>
                        <span class="badge <?php echo e($a->is_published ? 'badge-green' : 'badge-gray'); ?>">
                            <?php echo e($a->is_published ? 'منشور' : 'مسودة'); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>