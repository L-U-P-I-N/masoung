<?php $__env->startSection('title', $item->title); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <h1><?php echo e($item->title); ?></h1>
</div>

<section class="section">
    <div class="section-inner" style="max-width:860px">
        <?php if($item->image): ?>
        <?php
            $images = array_filter(array_map('trim', explode(',', $item->image)));
        ?>
        <div class="banner-container" id="newsBanner">
            <div class="banner-slider" id="mainSlider">
                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="banner-item" id="slide-<?php echo e($index); ?>">
                        <img src="<?php echo e(Storage::url($image)); ?>" alt="<?php echo e($item->title); ?>">
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
                <i class="fas fa-calendar" style="margin-left:0.4rem"></i><?php echo e(\Carbon\Carbon::parse($item->published_at)->format('d/m/Y')); ?>

            </span>
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2.5rem;line-height:2;font-size:1.05rem">
            <?php echo nl2br(e($item->content)); ?>

        </div>

        <div style="margin-top:2rem">
            <a href="<?php echo e(route('news')); ?>" style="color:var(--gold);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem">
                <i class="fas fa-arrow-right"></i> العودة للأخبار
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

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/public/news_show.blade.php ENDPATH**/ ?>