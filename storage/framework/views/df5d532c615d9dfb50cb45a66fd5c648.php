<?php $__env->startSection('title', 'الأخبار'); ?>
<?php $__env->startSection('page-title', 'إدارة الأخبار'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الأخبار'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <?php
        $loggedAdmin = \Illuminate\Support\Facades\DB::table('admins')->where('id', session('admin_id'))->first();
        $perms = $loggedAdmin ? (json_decode($loggedAdmin->permissions, true) ?: []) : [];
        $isSuper = $loggedAdmin && $loggedAdmin->role === 'super_admin';
    ?>

    <?php if($isSuper || in_array('manage_news', $perms)): ?>
    <a href="<?php echo e(route('admin.news.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة خبر
    </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="table-wrap">
    <div class="table-head">
        <h2>قائمة الأخبار (<?php echo e(count($news)); ?>)</h2>
    </div>
    <?php if($news->isEmpty()): ?>
        <div class="empty">
            <i class="fas fa-newspaper"></i>
            لا توجد أخبار حتى الآن
            <br><br>
            <?php if($isSuper || in_array('manage_news', $perms)): ?>
            <a href="<?php echo e(route('admin.news.create')); ?>" class="btn btn-primary">إضافة أول خبر</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
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
                <?php if($isSuper || in_array('manage_news', $perms)): ?>
                <th>الإجراءات</th>
                <?php else: ?>
                <th>عرض</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="color:var(--text-muted);font-size:0.8rem"><?php echo e($n->id); ?></td>
                <td>
                    <div class="td-img" style="position:relative">
                        <?php if($n->image): ?>
                            <?php $firstImg = trim(explode(',', $n->image)[0]); $imgCount = count(array_filter(explode(',', $n->image))); ?>
                            <img src="<?php echo e(Storage::url($firstImg)); ?>" alt="">
                            <?php if($imgCount > 1): ?>
                                <span style="position:absolute;bottom:0;right:0;background:rgba(0,0,0,0.65);color:#fff;font-size:10px;padding:1px 5px;border-radius:3px 0 0 0">+<?php echo e($imgCount); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <i class="fas fa-newspaper"></i>
                        <?php endif; ?>
                    </div>
                </td>
                <td><strong><?php echo e(Str::limit($n->title, 40)); ?></strong></td>
                <td style="color:var(--text-muted);font-size:0.82rem"><?php echo e(Str::limit($n->excerpt, 60)); ?></td>
                <td>
                    <span class="badge <?php echo e($n->is_published ? 'badge-green' : 'badge-gray'); ?>">
                        <?php echo e($n->is_published ? 'منشور' : 'مسودة'); ?>

                    </span>
                </td>
                <td style="font-size:0.82rem;color:var(--text-muted);direction:ltr">
                    <?php echo e($n->published_at ? \Carbon\Carbon::parse($n->published_at)->format('d/m/Y') : '—'); ?>

                </td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        <a href="<?php echo e(route('news.show', $n->id)); ?>" target="_blank" class="btn btn-edit" title="معاينة">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if($isSuper || in_array('manage_news', $perms)): ?>
                        <a href="<?php echo e(route('admin.news.edit', $n->id)); ?>" class="btn btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form method="POST" action="<?php echo e(route('admin.news.delete', $n->id)); ?>"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-del"><i class="fas fa-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/news/index.blade.php ENDPATH**/ ?>