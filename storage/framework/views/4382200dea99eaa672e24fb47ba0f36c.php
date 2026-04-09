<?php $__env->startSection('title', $item ? 'تعديل خبر' : 'إضافة خبر'); ?>
<?php $__env->startSection('page-title', $item ? 'تعديل الخبر' : 'إضافة خبر جديد'); ?>
<?php $__env->startSection('breadcrumb', 'الرئيسية / الأخبار / ' . ($item ? 'تعديل' : 'إضافة')); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <a href="<?php echo e(route('admin.news')); ?>" class="btn btn-edit">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST"
    action="<?php echo e($item ? route('admin.news.update', $item->id) : route('admin.news.store')); ?>"
    enctype="multipart/form-data"
    id="newsForm">
    <?php echo csrf_field(); ?>
    <?php if($item): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div class="form-card">
        <div class="form-grid">

            <div class="form-group full">
                <label class="lbl">عنوان الخبر <span style="color:var(--red)">*</span></label>
                <input type="text" name="title" value="<?php echo e(old('title', $item->title ?? '')); ?>" placeholder="أدخل عنوان الخبر" required>
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="form-group full">
                <label class="lbl">الصور</label>

                
                <div id="dropZone" style="border:2px dashed var(--gold);border-radius:10px;padding:1.5rem;background:var(--dark2);text-align:center;cursor:pointer;transition:background 0.2s" onclick="document.getElementById('fileInput').click()" ondragover="handleDragOver(event)" ondrop="handleDrop(event)">
                    <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:var(--gold);display:block;margin-bottom:0.5rem"></i>
                    <p style="color:var(--text-muted);margin:0;font-size:0.9rem">اسحب وأفلت الصور هنا، أو <span style="color:var(--gold);font-weight:600">اضغط للاختيار</span></p>
                    <p style="color:var(--text-muted);font-size:0.75rem;margin:0.3rem 0 0">JPG, PNG, WEBP — بحد أقصى 20 ميجا للصورة</p>
                    <input type="file" id="fileInput" name="images[]" accept="image/jpeg,image/png,image/webp" multiple style="display:none" onchange="handleFileSelect(this.files)">
                </div>

                
                <?php if($item && $item->image): ?>
                    <?php $existingImages = array_filter(array_map('trim', explode(',', $item->image))); ?>
                    <?php if(count($existingImages) > 0): ?>
                    <div style="margin-top:1rem">
                        <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:0.5rem"><i class="fas fa-images"></i> الصور الحالية:</p>
                        <div id="existingImagesGrid" style="display:flex;flex-wrap:wrap;gap:0.6rem">
                            <?php $__currentLoopData = $existingImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="img-thumb" id="existing_<?php echo e($index); ?>" style="position:relative;width:90px;height:90px;border-radius:8px;overflow:hidden;border:2px solid var(--border)">
                                <img src="<?php echo e(Storage::url($img)); ?>" style="width:100%;height:100%;object-fit:cover">
                                <input type="hidden" name="existing_images[]" value="<?php echo e($img); ?>" id="ei_<?php echo e($index); ?>">
                                <button type="button" onclick="removeExisting(<?php echo e($index); ?>)" title="حذف" style="position:absolute;top:3px;right:3px;background:rgba(220,38,38,0.9);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:13px;line-height:1;display:flex;align-items:center;justify-content:center">×</button>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                
                <div id="newImagesGrid" style="display:flex;flex-wrap:wrap;gap:0.6rem;margin-top:0.8rem"></div>

                <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group full">
                <label class="lbl">ملخص الخبر <span style="color:var(--red)">*</span></label>
                <textarea name="excerpt" placeholder="ملخص مختصر يظهر في قوائم الأخبار..." required><?php echo e(old('excerpt', $item->excerpt ?? '')); ?></textarea>
                <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group full">
                <label class="lbl">محتوى الخبر الكامل <span style="color:var(--red)">*</span></label>
                <textarea name="content" style="min-height:250px" placeholder="اكتب تفاصيل الخبر الكاملة هنا..." required><?php echo e(old('content', $item->content ?? '')); ?></textarea>
                <?php $__errorArgs = ['content'];
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
                    <input type="checkbox" id="is_published" name="is_published"
                        <?php echo e(old('is_published', $item->is_published ?? true) ? 'checked' : ''); ?>>
                    <label for="is_published" class="lbl" style="color:var(--text)">نشر الخبر (يظهر للزوار فوراً)</label>
                </div>
            </div>

        </div>

        <div style="border-top:1px solid var(--border);margin-top:1.5rem;padding-top:1.5rem;display:flex;gap:1rem">
            <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;font-size:0.95rem">
                <i class="fas fa-save"></i>
                <?php echo e($item ? 'حفظ التعديلات' : 'نشر الخبر'); ?>

            </button>
            <a href="<?php echo e(route('admin.news')); ?>" class="btn btn-edit" style="padding:0.75rem 1.5rem">إلغاء</a>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ===== إدارة الصور الجديدة =====
let selectedFiles = [];

function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('dropZone').style.background = 'rgba(212,175,55,0.08)';
}

document.getElementById('dropZone').addEventListener('dragleave', function() {
    this.style.background = 'var(--dark2)';
});

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    document.getElementById('dropZone').style.background = 'var(--dark2)';
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    if (files.length) addFiles(files);
}

function handleFileSelect(files) {
    addFiles(Array.from(files));
}

function addFiles(files) {
    selectedFiles = [...selectedFiles, ...files];
    syncFileInput();
    renderNewPreviews();
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('fileInput').files = dt.files;
}

function removeNewImage(index) {
    selectedFiles.splice(index, 1);
    syncFileInput();
    renderNewPreviews();
}

function renderNewPreviews() {
    const grid = document.getElementById('newImagesGrid');
    grid.innerHTML = '';
    if (selectedFiles.length === 0) return;

    const label = document.createElement('p');
    label.style.cssText = 'width:100%;color:var(--text-muted);font-size:0.85rem;margin:0 0 0.3rem';
    label.innerHTML = '<i class="fas fa-upload"></i> صور جديدة (سيتم رفعها):';
    grid.appendChild(label);

    selectedFiles.forEach((file, i) => {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'position:relative;width:90px;height:90px;border-radius:8px;overflow:hidden;border:2px solid var(--gold)';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.cssText = 'width:100%;height:100%;object-fit:cover';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = '×';
        btn.title = 'حذف';
        btn.style.cssText = 'position:absolute;top:3px;right:3px;background:rgba(220,38,38,0.9);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;font-size:13px;line-height:1;display:flex;align-items:center;justify-content:center';
        btn.onclick = () => removeNewImage(i);

        const name = document.createElement('span');
        name.style.cssText = 'position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.6);color:#fff;font-size:9px;padding:2px 4px;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis';
        name.textContent = file.name;

        wrap.appendChild(img);
        wrap.appendChild(btn);
        wrap.appendChild(name);
        grid.appendChild(wrap);
    });
}

// ===== حذف الصور الحالية =====
function removeExisting(index) {
    const el = document.getElementById('existing_' + index);
    if (el) {
        el.style.opacity = '0.3';
        el.style.transform = 'scale(0.85)';
        el.style.transition = '0.2s';
        setTimeout(() => el.remove(), 200);
    }
    const inp = document.getElementById('ei_' + index);
    if (inp) inp.remove();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/admin/news/form.blade.php ENDPATH**/ ?>