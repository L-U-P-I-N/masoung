<?php $__env->startSection('title', $activity->title); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <h1><?php echo e($activity->title); ?></h1>
</div>

<section class="section">
    <div class="section-inner" style="max-width:860px">
        <?php if($activity->image): ?>
        <?php
            $images = array_filter(array_map('trim', explode(',', $activity->image)));
        ?>
        <div class="banner-container" id="activityBanner">
            <div class="banner-slider" id="mainSlider">
                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="banner-item" id="slide-<?php echo e($index); ?>">
                        <img src="<?php echo e(Storage::url($image)); ?>" alt="<?php echo e($activity->title); ?>">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php if(count($images) > 1): ?>
                <div class="banner-nav prev" onclick="moveSlider(-1)"><i class="fas fa-chevron-right"></i></div>
                <div class="banner-nav next" onclick="moveSlider(1)"><i class="fas fa-chevron-left"></i></div>
                
                <div class="banner-dots">
                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="banner-dot <?php echo e($index === 0 ? 'active' : ''); ?>" onclick="scrollToSlide(<?php echo e($index); ?>)"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-bottom:2rem">
            <span style="background:rgba(201,168,76,0.1);color:var(--gold);padding:0.4rem 1rem;border-radius:8px;font-size:0.85rem">
                <i class="fas fa-calendar" style="margin-left:0.4rem"></i><?php echo e(\Carbon\Carbon::parse($activity->activity_date)->format('d/m/Y')); ?>

            </span>
            <?php if($activity->location): ?>
            <span style="background:rgba(201,168,76,0.1);color:var(--gold);padding:0.4rem 1rem;border-radius:8px;font-size:0.85rem">
                <i class="fas fa-map-marker-alt" style="margin-left:0.4rem"></i><?php echo e($activity->location); ?>

            </span>
            <?php endif; ?>
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2.5rem;line-height:2;font-size:1.05rem">
            <?php echo nl2br(e($activity->content ?? $activity->description)); ?>

        </div>

        <div style="margin-top:2rem">
            <a href="<?php echo e(route('activities')); ?>" style="color:var(--gold);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem">
                <i class="fas fa-arrow-right"></i> العودة للأنشطة
            </a>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    const slider = document.getElementById('mainSlider');
    const dots = document.querySelectorAll('.banner-dot');

    function scrollToSlide(index) {
        const slideWidth = slider.offsetWidth;
        slider.scrollTo({
            left: index * slideWidth,
            behavior: 'smooth'
        });
    }

    function moveSlider(direction) {
        const slideWidth = slider.offsetWidth;
        // In RTL, scrollLeft is negative or handled differently by browsers
        slider.scrollBy({
            left: direction * slideWidth,
            behavior: 'smooth'
        });
    }

    slider.addEventListener('scroll', () => {
        const slideWidth = slider.offsetWidth;
        const index = Math.round(Math.abs(slider.scrollLeft) / slideWidth);
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/public/activity_show.blade.php ENDPATH**/ ?>