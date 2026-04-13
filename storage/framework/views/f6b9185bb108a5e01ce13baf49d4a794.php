<?php $__env->startSection('title', 'إعدادات القبيلة'); ?>
<?php $__env->startSection('page-title', 'إعدادات القبيلة'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الإعدادات'); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <div class="form-card">
        <h2 style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);color:var(--gold)">
            <i class="fas fa-cog" style="margin-left:0.5rem"></i> بيانات القبيلة الأساسية
        </h2>

        <div class="form-grid">

            <div class="form-group">
                <label class="lbl">اسم القبيلة <span style="color:var(--red)">*</span></label>
                <input type="text" name="tribe_name" value="<?php echo e(old('tribe_name', optional($settings)->tribe_name ?? 'قبيلة مسونق')); ?>" required>
                <?php $__errorArgs = ['tribe_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">تاريخ التأسيس</label>
                <input type="date" name="founded_date" value="<?php echo e(old('founded_date', optional($settings)->founded_date ?? '')); ?>">
                <?php $__errorArgs = ['founded_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">الموقع الجغرافي</label>
                <input type="text" name="location" value="<?php echo e(old('location', optional($settings)->location ?? '')); ?>" placeholder="مثال: المنطقة الشرقية، المملكة العربية السعودية">
                <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">البريد الإلكتروني للتواصل</label>
                <input type="email" name="contact_email" value="<?php echo e(old('contact_email', optional($settings)->contact_email ?? '')); ?>" placeholder="info@tribe.com" style="direction:ltr">
                <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">رقم الهاتف</label>
                <input type="tel" name="contact_phone" value="<?php echo e(old('contact_phone', optional($settings)->contact_phone ?? '')); ?>" placeholder="+966500000000" style="direction:ltr">
                <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">شعار القبيلة (Logo)</label>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                <?php if($settings && $settings->logo): ?>
                <div style="margin-top:0.7rem">
                    <img src="<?php echo e(Storage::url($settings->logo)); ?>" class="img-preview" alt="الشعار">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.3rem">الشعار الحالي</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="lbl">صورة الغلاف</label>
                <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp">
                <?php if($settings && $settings->cover_image): ?>
                <div style="margin-top:0.7rem">
                    <img src="<?php echo e(Storage::url($settings->cover_image)); ?>" class="img-preview" alt="الغلاف" style="width:200px;height:100px">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.3rem">صورة الغلاف الحالية</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="form-group full">
                <label class="lbl">وصف القبيلة</label>
                <textarea name="tribe_description" style="min-height:150px" placeholder="وصف مختصر عن القبيلة يظهر في الصفحة الرئيسية وصفحة عن القبيلة..."><?php echo e(old('tribe_description', optional($settings)->tribe_description ?? '')); ?></textarea>
                <?php $__errorArgs = ['tribe_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div> 

        <div class="form-grid" style="margin-top:1.2rem; border-top:1px solid rgba(201,168,76,0.1); padding-top:1.2rem;">
            <div class="form-group">
                <label class="lbl">تكرار النسخ الاحتياطي التلقائي</label>
                <select name="backup_frequency">
                    <option value="daily" <?php echo e(old('backup_frequency', optional($settings)->backup_frequency ?? 'monthly') == 'daily' ? 'selected' : ''); ?>>يومياً</option>
                    <option value="weekly" <?php echo e(old('backup_frequency', optional($settings)->backup_frequency ?? 'monthly') == 'weekly' ? 'selected' : ''); ?>>أسبوعياً</option>
                    <option value="monthly" <?php echo e(old('backup_frequency', optional($settings)->backup_frequency ?? 'monthly') == 'monthly' ? 'selected' : ''); ?>>شهرياً (مرة في الشهر)</option>
                    <option value="yearly" <?php echo e(old('backup_frequency', optional($settings)->backup_frequency ?? 'monthly') == 'yearly' ? 'selected' : ''); ?>>سنوياً</option>
                    <option value="none" <?php echo e(old('backup_frequency', optional($settings)->backup_frequency ?? 'monthly') == 'none' ? 'selected' : ''); ?>>إيقاف النسخ التلقائي</option>
                </select>
                <?php $__errorArgs = ['backup_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    <div class="form-card" style="margin-top:1.5rem">
        <h2 style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);color:var(--gold)">
            <i class="fas fa-magic" style="margin-left:0.5rem"></i> إعدادات الموافقة التلقائية
        </h2>
        <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem">
            يمكنك تحديد فترة زمنية يتم فيها قبول أي عضو جديد يسجل في الموقع تلقائياً دون الحاجة لموافقة يدوية.
        </p>

        <div class="form-grid">
            <div class="form-group">
                <label class="lbl">تاريخ ووقت البدء</label>
                <input type="datetime-local" name="auto_approve_start" value="<?php echo e(old('auto_approve_start', $settings && $settings->auto_approve_start ? date('Y-m-d\TH:i', strtotime($settings->auto_approve_start)) : '')); ?>">
                <?php $__errorArgs = ['auto_approve_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">تاريخ ووقت الانتهاء</label>
                <input type="datetime-local" name="auto_approve_end" value="<?php echo e(old('auto_approve_end', $settings && $settings->auto_approve_end ? date('Y-m-d\TH:i', strtotime($settings->auto_approve_end)) : '')); ?>">
                <?php $__errorArgs = ['auto_approve_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    <div class="form-card" style="margin-top:1.5rem">
        <div style="padding:0.5rem 0">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-save"></i> حفظ الإعدادات
            </button>
        </div>
    </div>
</form>


<div class="form-card" style="margin-top:1.5rem">
    <h2 style="margin-bottom:0.5rem;color:var(--gold)">
        <i class="fas fa-lock" style="margin-left:0.5rem"></i> الحماية والأمان
    </h2>
    <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem">يمكنك تغيير كلمة المرور الخاصة بحساب الادمن من هنا لضمان أمان الموقع.</p>
    
    <a href="<?php echo e(route('admin.password.edit')); ?>" class="btn btn-secondary" style="text-decoration:none;display:inline-flex;align-items:center;gap:0.7rem;padding:0.7rem 1.5rem;background:var(--dark3);border:1px solid var(--border);color:var(--gold-light);border-radius:10px;font-weight:600">
        <i class="fas fa-key"></i> تغيير كلمة المرور
    </a>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/settings.blade.php ENDPATH**/ ?>