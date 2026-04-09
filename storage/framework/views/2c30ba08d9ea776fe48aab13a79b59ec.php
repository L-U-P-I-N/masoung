<?php $__env->startSection('title', $user ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="topbar">
    <div>
        <h1><?php echo e($user ? 'تعديل بيانات المستخدم: ' . $user->name : 'إضافة مستخدم جديد'); ?></h1>
        <div class="breadcrumb"><a href="<?php echo e(route('admin.users')); ?>" style="color:inherit;text-decoration:none;">إدارة المستخدمين</a> / <?php echo e($user ? 'تعديل' : 'إضافة'); ?></div>
    </div>
    <div class="topbar-actions">
        <a href="<?php echo e(route('admin.users')); ?>" class="btn" style="background:var(--dark3); border:1px solid var(--border); color:var(--text);"><i class="fas fa-arrow-right"></i> عودة</a>
    </div>
</div>

<div class="content">
    <?php if($errors->any()): ?>
        <div class="alert alert-error">يرجى التأكد من صحة البيانات المدخلة.</div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="form-card" style="max-width:800px; margin:0 auto;">
        <form action="<?php echo e($user ? route('admin.users.update', $user->id) : route('admin.users.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php if($user): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="lbl">الاسم الكامل <span style="color:var(--red)">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name ?? '')); ?>" required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="lbl">البريد الإلكتروني <span style="color:var(--red)">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email ?? '')); ?>" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group full">
                    <label class="lbl">كلمة المرور <?php echo e($user ? '(اترك الحقل فارغاً إذا لم ترغب بتغييرها)' : '*'); ?></label>
                    <input type="password" name="password" <?php echo e($user ? '' : 'required'); ?>>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <?php if(!$user || $user->role !== 'super_admin'): ?>
                <div class="form-group full" style="margin-top:1rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                    <label class="lbl" style="font-size:1rem; color:var(--gold); margin-bottom:1rem;"><i class="fas fa-key"></i> صلاحيات المشرف</label>
                    
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                        <?php $perms = $user ? $user->permissions : []; ?>
                        
                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_members" name="permissions[]" value="manage_members" <?php echo e(in_array('manage_members', $perms) ? 'checked' : ''); ?>>
                            <label for="perm_members">
                                <strong style="display:block; color:#fff;">إدارة الأعضاء</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">مراجعة، إضافة وتعديل حسابات القبيلة</span>
                            </label>
                        </div>

                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_news" name="permissions[]" value="manage_news" <?php echo e(in_array('manage_news', $perms) ? 'checked' : ''); ?>>
                            <label for="perm_news">
                                <strong style="display:block; color:#fff;">إدارة الأخبار</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">نشر، تعديل وحذف الأخبار</span>
                            </label>
                        </div>

                        <div class="toggle-wrap" style="background:var(--dark3); border:1px solid var(--border); border-radius:10px; padding:1rem;">
                            <input type="checkbox" id="perm_activities" name="permissions[]" value="manage_activities" <?php echo e(in_array('manage_activities', $perms) ? 'checked' : ''); ?>>
                            <label for="perm_activities">
                                <strong style="display:block; color:#fff;">إدارة الأنشطة</strong>
                                <span style="font-size:0.75rem; color:var(--text-muted);">نشر وتحديث الفعاليات والأنشطة</span>
                            </label>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="form-group full" style="margin-top:1rem; padding:1rem; background:rgba(212,175,55,0.1); border:1px dashed var(--gold); border-radius:10px; text-align:center;">
                    <i class="fas fa-crown" style="color:var(--gold); font-size:2rem; margin-bottom:0.5rem;"></i>
                    <p style="color:var(--gold); font-weight:bold; margin:0;">هذا الحساب هو "المدير العام" ويمتلك كافة الصلاحيات على النظام.</p>
                </div>
                <?php endif; ?>
            </div>

            <div style="margin-top:2rem; text-align:left;">
                <button type="submit" class="btn btn-primary" style="padding:0.8rem 2rem; font-size:1rem;"><i class="fas fa-save"></i> حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/users/form.blade.php ENDPATH**/ ?>