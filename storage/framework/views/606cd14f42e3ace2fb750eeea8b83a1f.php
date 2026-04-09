<?php $__env->startSection('title', 'الأعضاء'); ?>
<?php $__env->startSection('page-title', 'إدارة الأعضاء'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الأعضاء'); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('admin.members.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة عضو
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="table-wrap">
    <div class="table-head" style="display:flex; justify-content:space-between; align-items:center;">
        <h2>قائمة الأعضاء (<?php echo e(count($members)); ?>)</h2>
        <div style="display:flex; gap:0.5rem">
            <form action="<?php echo e(route('admin.members.approve.all')); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من الموافقة على جميع الأعضاء المنتظرين؟')">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button type="submit" class="btn btn-primary" style="background:#10b981; padding:0.5rem 1rem">
                    <i class="fas fa-check-double"></i> قبول كل المنتظرين
                </button>
            </form>
            <button type="submit" form="bulkApproveForm" class="btn btn-primary" style="padding:0.5rem 1rem">
                <i class="fas fa-check"></i> قبول المحدد
            </button>
        </div>
    </div>

    <?php if($members->isEmpty()): ?>
        <div class="empty">
            <i class="fas fa-users"></i>
            لا يوجد أعضاء حتى الآن
            <br><br>
            <a href="<?php echo e(route('admin.members.create')); ?>" class="btn btn-primary">إضافة أول عضو</a>
        </div>
    <?php else: ?>
    <div style="overflow-x:auto">
    <form id="bulkApproveForm" action="<?php echo e(route('admin.members.approve.bulk')); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من الموافقة على الأعضاء المحددين؟')">
        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>المنصب</th>
                <th>المهنة</th>
                <th>رقم الهاتف</th>
                <th>الموقع</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if(!$m->is_active): ?>
                        <input type="checkbox" name="member_ids[]" value="<?php echo e($m->id); ?>" class="member-checkbox">
                    <?php else: ?>
                        - 
                    <?php endif; ?>
                </td>
                <td style="color:var(--text-muted);font-size:0.8rem"><?php echo e($m->id); ?></td>
                <td>
                    <div class="td-img">
                        <?php if($m->photo): ?>
                            <img src="<?php echo e(Storage::url($m->photo)); ?>" alt="<?php echo e($m->name); ?>">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                </td>
                <td><strong><?php echo e($m->name); ?></strong></td>
                <td style="color:var(--text-muted)"><?php echo e($m->position ?? 'member'); ?></td>
                <td style="color:var(--text-muted)"><?php echo e($m->profession ?? '---'); ?></td>
                <td style="direction:ltr;font-size:0.85rem"><?php echo e($m->phone ?? '---'); ?></td>
                <td style="color:var(--text-muted)"><?php echo e($m->location ?? '---'); ?></td>
                <td>
                    <span class="badge <?php echo e($m->is_active ? 'badge-green' : 'badge-yellow'); ?>">
                        <?php echo e($m->is_active ? 'نشط' : 'بانتظار الموافقة'); ?>

                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:0.5rem">
                        <?php if(!$m->is_active): ?>
                        <form action="<?php echo e(route('admin.members.approve', $m->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-primary" style="padding:0.3rem 0.6rem; font-size:0.75rem; background:var(--green)">
                                <i class="fas fa-check"></i> موافقة
                            </button>
                        </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.members.edit', $m->id)); ?>" class="btn btn-edit">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <form action="<?php echo e(route('admin.members.delete', $m->id)); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-delete">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </form>
    </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/members/index.blade.php ENDPATH**/ ?>