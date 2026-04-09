<?php $__env->startSection('title', $member ? 'تعديل عضو' : 'إضافة عضو'); ?>
<?php $__env->startSection('page-title', $member ? 'تعديل عضو' : 'إضافة عضو جديد'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الأعضاء / ' . ($member ? 'تعديل' : 'إضافة')); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('admin.members')); ?>" class="btn btn-edit">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<form method="POST"
    action="<?php echo e($member ? route('admin.members.update', $member->id) : route('admin.members.store')); ?>"
    enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($member): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="form-card">
        <div class="form-grid">

            <div class="form-group">
                <label class="lbl">الاسم الكامل <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" value="<?php echo e(old('name', $member->name ?? '')); ?>" placeholder="الاسم الثلاثي أو الرباعي" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">Profession <span style="color:var(--gray);font-size:0.85rem">(Job/Career)</span></label>
                <input type="text" name="profession" value="<?php echo e(old('profession', $member->profession ?? '')); ?>" placeholder="e.g., Engineer, Doctor, Teacher...">
                <?php $__errorArgs = ['profession'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">Location <span style="color:var(--gray);font-size:0.85rem">(City/Area)</span></label>
                <input type="text" name="location" value="<?php echo e(old('location', $member->location ?? '')); ?>" placeholder="e.g., Riyadh, Jeddah, Eastern Province...">
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
                <label class="lbl">المنصب / الدور <span style="color:var(--gray);font-size:0.85rem">(Tribal Role)</span></label>
                <input type="text" name="position" value="<?php echo e(old('position', $member->position ?? '')); ?>" placeholder="مثال: رئيس القبيلة، أمين الصندوق...">
                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">الدولة <span style="color:var(--red)">*</span></label>
                <input type="text" name="country" id="country-input" list="country-list" value="<?php echo e(old('country', $member->country ?? 'اليمن')); ?>" placeholder="ابحث أو اكتب اسم الدولة..." required oninput="updatePhoneValidation()">
                <datalist id="country-list">
                    <!-- JS will populate this -->
                </datalist>
                <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">المحافظة <span style="color:var(--red)">*</span></label>
                <input type="text" name="province" value="<?php echo e(old('province', $member->province ?? '')); ?>" required placeholder="بغداد / صنعاء...">
                <?php $__errorArgs = ['province'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">المدينة <span style="color:var(--red)">*</span></label>
                <input type="text" name="city" value="<?php echo e(old('city', $member->city ?? '')); ?>" required placeholder="الكرادة / حدة...">
                <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group" id="phone-container">
                <label class="lbl">رقم الهاتف <span id="phone-hint" style="font-size:0.7rem; color:var(--text-muted)">(أدخل الرقم المحلي)</span> <button type="button" class="btn" style="background:var(--dark3); color:var(--gold); border:1px solid var(--border); border-radius:5px; padding:0 0.5rem; margin-right: 0.5rem; font-size: 0.7rem;" onclick="addPhoneField()">+</button></label>
                <?php
                    $rawPhones = old('phone', ($member && $member->phone) ? explode(', ', $member->phone) : ['']);
                    if (empty($rawPhones)) $rawPhones = [''];
                ?>
                <?php $__currentLoopData = $rawPhones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $parts = explode(' ', $p, 2);
                        $num = (count($parts) > 1) ? $parts[1] : $parts[0];
                        $storedCode = (count($parts) > 1) ? $parts[0] : '+967';
                    ?>
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                    <div class="active-code-display" style="display:none;"><?php echo e($storedCode); ?></div>
                    <input type="tel" name="phone[]" class="phone-input" value="<?php echo e($num); ?>" placeholder="7XXXXXXXX" style="direction:ltr; flex:1" required>
                    <?php if(!$loop->first): ?>
                    <button type="button" class="btn" style="background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); border-radius:10px; padding:0 0.8rem;" onclick="this.parentElement.remove()">×</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__errorArgs = ['phone.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">ترتيب العرض</label>
                <input type="text" name="sort_order" value="<?php echo e(old('sort_order', $member->sort_order ?? 0)); ?>" placeholder="0">
                <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">صورة العضو</label>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
                <?php if($member && $member->photo): ?>
                <div style="margin-top:0.7rem">
                    <img src="<?php echo e(Storage::url($member->photo)); ?>" class="img-preview" alt="الصورة الحالية">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.3rem">الصورة الحالية — ارفع صورة جديدة للاستبدال</p>
                </div>
                <?php endif; ?>
                <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group full">
                <label class="lbl">نبذة تعريفية</label>
                <textarea name="bio" placeholder="نبذة مختصرة عن العضو..."><?php echo e(old('bio', $member->bio ?? '')); ?></textarea>
                <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <div class="toggle-wrap">
                    <input type="checkbox" id="is_active" name="is_active"
                        <?php echo e(old('is_active', $member->is_active ?? true) ? 'checked' : ''); ?>>
                    <label for="is_active" class="lbl" style="color:var(--text)">عضو نشط (يظهر في القائمة)</label>
                </div>
            </div>

        </div>

        <div style="border-top:1px solid var(--border);margin-top:1.5rem;padding-top:1.5rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-save"></i>
                <?php echo e($member ? 'حفظ التعديلات' : 'إضافة العضو'); ?>

            </button>
            <a href="<?php echo e(route('admin.members')); ?>" class="btn btn-edit" style="padding:0.75rem 1.5rem">إلغاء</a>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function updatePhoneValidation() {
    const select = document.getElementById('country-select');
    const option = select.options[select.selectedIndex];
    const code = option.getAttribute('data-code');
    const len = option.getAttribute('data-len');
    
    // Update all code displays
    document.querySelectorAll('.active-code-display').forEach(div => {
        div.textContent = code;
    });
    document.getElementById('phone-hint').textContent = `(${len} أرقام)`;
    
    // Update all phone inputs
    const phones = document.querySelectorAll('input[name="phone[]"]');
    phones.forEach(input => {
        input.pattern = `[0-9]{${len}}`;
        input.placeholder = "X".repeat(len);
        input.title = `يرجى إدخال ${len} أرقام`;
    });
}

function addPhoneField() {
    const input = document.getElementById('country-input');
    const val = input.value.trim();
    const config = countryData[val] || { code: '+', len: '7,15' };
    
    const container = document.getElementById('phone-container');
    const div = document.createElement('div');
    div.style.display = 'flex'; div.style.gap = '0.5rem'; div.style.marginBottom = '0.5rem';
    const pattern = config.len === '7,15' ? '[0-9]{7,15}' : `[0-9]{${config.len}}`;
    const placeholder = config.len === '7,15' ? 'رقم الهاتف' : 'X'.repeat(config.len);
    
    div.innerHTML = `
        <div class="active-code-display" style="display:none;">${config.code}</div>
        <input type="tel" name="phone[]" class="phone-input" placeholder="${placeholder}" style="direction:ltr; flex:1" required pattern="${pattern}">
        <button type="button" class="btn" style="background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); border-radius:10px; padding:0 0.8rem;" onclick="this.parentElement.remove()">×</button>
    `;
    container.appendChild(div);
}

// Init
document.addEventListener('DOMContentLoaded', updatePhoneValidation);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/members/form.blade.php ENDPATH**/ ?>