<?php $__env->startSection('title', 'تغيير كلمة المرور'); ?>
<?php $__env->startSection('page-title', 'تغيير كلمة المرور'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الإعدادات / تغيير كلمة المرور'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);color:var(--gold)">
        <i class="fas fa-key" style="margin-left:0.5rem"></i> تعيين كلمة مرور جديدة
    </h2>

    <form method="POST" action="<?php echo e(route('admin.password.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-group">
            <label class="lbl">كلمة المرور الحالية <span style="color:var(--red)">*</span></label>
            <input type="password" name="current_password" required>
            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="lbl">كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
            <input type="password" name="password" required>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="lbl">تأكيد كلمة المرور الجديدة <span style="color:var(--red)">*</span></label>
            <input type="password" name="password_confirmation" required>
        </div>

        <div style="border-top:1px solid var(--border);margin-top:1.5rem;padding-top:1.5rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-check-circle"></i> تحديث كلمة المرور
            </button>
            <a href="<?php echo e(route('admin.settings')); ?>" class="btn btn-secondary" style="padding:0.75rem 2rem;font-size:0.95rem;text-decoration:none;display:inline-flex;align-items:center;color:white;border:1px solid var(--border);border-radius:10px">
                إلغاء
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/password.blade.php ENDPATH**/ ?>