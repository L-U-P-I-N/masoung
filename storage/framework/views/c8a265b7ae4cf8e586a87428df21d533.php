<?php $__env->startSection('title', 'إدارة مدراء النظام'); ?>

<?php $__env->startSection('content'); ?>
<div class="topbar">
    <div>
        <h1>مدراء النظام</h1>
        <div class="breadcrumb">لوحة التحكم / إدارة المستخدمين</div>
    </div>
    <div class="topbar-actions">
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مدير جديد</a>
    </div>
</div>

<div class="content">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid var(--border); font-size:0.9rem;">
                    <td style="padding:1rem; font-weight:600;"><?php echo e($user->name); ?></td>
                    <td style="padding:1rem; color:var(--text-muted);"><?php echo e($user->email); ?></td>
                    <td style="padding:1rem;">
                        <?php if($user->role === 'super_admin'): ?>
                            <span style="background:rgba(212,175,55,0.2); color:var(--gold); padding:0.2rem 0.6rem; border-radius:4px; font-size:0.75rem; font-weight:bold;">مدير عام</span>
                        <?php else: ?>
                            <span style="background:rgba(59,130,246,0.2); color:#93c5fd; padding:0.2rem 0.6rem; border-radius:4px; font-size:0.75rem;">مشرف</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:1rem; color:var(--text-muted);"><?php echo e(\Carbon\Carbon::parse($user->created_at)->format('Y-m-d')); ?></td>
                    <td style="padding:1rem; text-align:left;">
                        <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn btn-edit" title="تعديل"><i class="fas fa-edit"></i></a>
                        <?php if($user->role !== 'super_admin'): ?>
                        <form action="<?php echo e(route('admin.users.delete', $user->id)); ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشرف؟');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-del" title="حذف"><i class="fas fa-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="empty">لا يوجد مدراء حالياً</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/users/index.blade.php ENDPATH**/ ?>