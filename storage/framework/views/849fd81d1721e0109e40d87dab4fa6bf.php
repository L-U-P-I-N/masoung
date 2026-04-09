<?php $__env->startSection('title', 'سجل نشاطات المشرفين'); ?>
<?php $__env->startSection('page-title', 'سجل النشاطات'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / سجل النشاطات'); ?>

<?php $__env->startSection('content'); ?>
<div class="topbar">
    <div>
        <h1>سجل نشاطات المشرفين</h1>
        <div class="breadcrumb">لوحة التحكم / سجل النشاطات</div>
    </div>
    <div class="topbar-actions">
        <form action="<?php echo e(route('admin.logs.clear')); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من مسح جميع السجلات؟ هذا الإجراء لا يمكن التراجع عنه.');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-del"><i class="fas fa-trash-alt"></i> إفراغ السجل بالكامل</button>
        </form>
    </div>
</div>

<div class="content">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="table-wrap">
        <div class="table-head">
            <h2><i class="fas fa-history" style="color:var(--gold); margin-left:8px;"></i> قائمة الإجراءات الأخيرة</h2>
        </div>
        
        <?php if($logs->isEmpty()): ?>
            <div class="empty">
                <i class="fas fa-box-open"></i>
                السجل فارغ حالياً
            </div>
        <?php else: ?>
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
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem"><?php echo e($log->id); ?></td>
                        <td style="font-weight:600; color:var(--gold);">
                            <i class="fas fa-user-shield" style="font-size:0.8rem; margin-left:4px;"></i> <?php echo e($log->admin_name); ?>

                        </td>
                        <td>
                            <?php if($log->action == 'created'): ?> <span class="badge badge-green">أضاف</span>
                            <?php elseif($log->action == 'updated'): ?> <span class="badge" style="background:rgba(59,130,246,0.12);color:#93c5fd;">عدل</span>
                            <?php elseif($log->action == 'deleted'): ?> <span class="badge" style="background:rgba(239,68,68,0.12);color:#fca5a5;">حذف</span>
                            <?php elseif($log->action == 'approved'): ?> <span class="badge" style="background:rgba(212,175,55,0.12);color:var(--gold);">وافق على</span>
                            <?php else: ?> <span class="badge badge-gray"><?php echo e($log->action); ?></span> <?php endif; ?>
                        </td>
                        <td>
                            <?php if($log->model_type == 'member'): ?> أعضاء القبيلة
                            <?php elseif($log->model_type == 'activity'): ?> الأنشطة
                            <?php elseif($log->model_type == 'news'): ?> الأخبار
                            <?php endif; ?>
                        </td>
                        <td style="color:var(--text-muted); font-size:0.85rem;">
                            <?php $details = json_decode($log->details, true); ?>
                            <?php if($details && isset($details['title'])): ?> <?php echo e(Str::limit($details['title'], 40)); ?>

                            <?php elseif($details && isset($details['name'])): ?> <?php echo e(Str::limit($details['name'], 40)); ?>

                            <?php else: ?> — <?php endif; ?>
                        </td>
                        <td style="font-size:0.8rem; color:var(--text-muted); direction:ltr;">
                            <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 1rem;">
            <?php echo e($logs->links('pagination::default')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/activity_logs.blade.php ENDPATH**/ ?>