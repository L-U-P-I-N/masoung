<?php $__env->startSection('title', 'الأخبار'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <h1>أخبار القبيلة</h1>
</div>

<section class="section">
    <div class="section-inner">
        <?php if($news->isEmpty()): ?>
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--border)"></i>
                لا توجد أخبار مضافة حتى الآن
            </div>
        <?php else: ?>
        <div class="cards-grid">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="card-img">
                    <?php
                        $imageString = $item->image ?? '';
                        $imageArray = array_filter(array_map('trim', explode(',', $imageString)));
                        $firstImg = count($imageArray) > 0 ? $imageArray[0] : null;
                    ?>
                    <?php if($firstImg): ?>
                        <img src="<?php echo e(Storage::url($firstImg)); ?>" alt="<?php echo e($item->title); ?>" style="width:100%;height:200px;object-fit:cover;border-radius:8px;">
                    <?php else: ?>
                        <i class="fas fa-newspaper"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-date"><i class="fas fa-calendar-alt" style="margin-left:4px"></i><?php echo e(\Carbon\Carbon::parse($item->published_at)->format('d/m/Y')); ?></div>
                    <h3 class="card-title"><?php echo e($item->title); ?></h3>
                    <p class="card-desc"><?php echo e(Str::limit($item->excerpt, 110)); ?></p>
                    <a href="<?php echo e(route('news.show', $item->id)); ?>" class="card-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="margin-top:3rem"><?php echo e($news->links()); ?></div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/public/news.blade.php ENDPATH**/ ?>