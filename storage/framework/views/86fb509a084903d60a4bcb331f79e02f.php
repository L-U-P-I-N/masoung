<?php $__env->startSection('title', 'الرئيسية'); ?>

<?php $__env->startSection('content'); ?>


<section class="section" style="padding: 100px 0 0; background: var(--dark);">
    <div class="section-inner">
        <div class="banner-container" style="margin-bottom: 0; border-radius: 20px;">
            <div class="banner-slider" id="homeSlider">
                <?php $__currentLoopData = $sliderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // تنظيف المسار والتأكد من أخذ أول صورة فعلياً حتى لو كانت هناك فواصل زائدة
                        $imageString = $item->image ?? '';
                        $imageArray = array_filter(array_map('trim', explode(',', $imageString)));
                        $firstImg = count($imageArray) > 0 ? $imageArray[0] : null;
                    ?>
                    <div class="banner-item" style="height: 450px;">
                        <?php if($firstImg): ?>
                            <img src="<?php echo e(Storage::url($firstImg)); ?>" alt="<?php echo e($item->title); ?>">
                        <?php else: ?>
                            <div style="background:var(--dark3);width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image" style="font-size:5rem;color:var(--border)"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="hero-banner-content" style="background: linear-gradient(to top, rgba(13,17,23,0.8), transparent); justify-content: flex-end; padding-bottom: 3rem;">
                            <span class="banner-label"><?php echo e($item->slider_label); ?></span>
                            <h2 class="banner-title" style="font-size: 2.2rem; margin-bottom: 1.5rem;"><?php echo e($item->title); ?></h2>
                            <a href="<?php echo e($item->slider_url); ?>" class="banner-btn">
                                <span>قراءة التفاصيل</span>
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($sliderItems->count() > 1): ?>
                <div class="banner-nav prev" onclick="moveHomeSlider(-1)"><i class="fas fa-chevron-right"></i></div>
                <div class="banner-nav next" onclick="moveHomeSlider(1)"><i class="fas fa-chevron-left"></i></div>
                
                <div class="banner-dots">
                    <?php $__currentLoopData = $sliderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="banner-dot <?php echo e($index === 0 ? 'active' : ''); ?>" onclick="scrollHomeSlide(<?php echo e($index); ?>)"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="hero" style="min-height: auto; padding: 4rem 2rem;">
    <div class="hero-bg"></div>
    <div class="hero-pattern"></div>
    <div class="hero-content">
        <h1 class="hero-title" style="font-size: clamp(2.5rem, 6vw, 4rem);"><?php echo e($settings->tribe_name ?? 'قبيلة مسونق'); ?></h1>
        <p class="hero-sub"><?php echo e($settings->tribe_description ?? 'نحمل إرث الأجداد ونبني مستقبل الأحفاد'); ?></p>

        <div class="hero-stats" style="margin-bottom: 0;">
            <div class="hero-stat">
                <div class="num counter" data-target="<?php echo e($membersCount); ?>">0</div>
                <div class="lbl">عضو</div>
            </div>
            <div class="hero-stat">
                <div class="num counter" data-target="<?php echo e($activitiesCount); ?>">0</div>
                <div class="lbl">نشاط</div>
            </div>
            <div class="hero-stat">
                <div class="num counter" data-target="<?php echo e($newsCount); ?>">0</div>
                <div class="lbl">خبر</div>
            </div>
        </div>
    </div>
</section>


<?php if($latestNews->count()): ?>
<section class="section" style="background:var(--dark2)">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-label">أحدث ما نشر</span>
            <h2 class="section-title">آخر الأخبار</h2>
            <div class="section-line"></div>
        </div>
        <div class="cards-grid">
            <?php $__currentLoopData = $latestNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="card-img">
                    <?php if($item->image): ?>
                        <?php $imgNews = trim(explode(',', $item->image)[0]); ?>
                        <img src="<?php echo e(Storage::url($imgNews)); ?>" alt="<?php echo e($item->title); ?>">
                    <?php else: ?>
                        <i class="fas fa-newspaper"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-date"><i class="fas fa-calendar-alt" style="margin-right:4px"></i><?php echo e(\Carbon\Carbon::parse($item->published_at)->format('d/m/Y')); ?></div>
                    <h3 class="card-title"><?php echo e($item->title); ?></h3>
                    <p class="card-desc"><?php echo e(Str::limit($item->excerpt, 100)); ?></p>
                    <a href="<?php echo e(route('news.show', $item->id)); ?>" class="card-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="text-align:center;margin-top:2.5rem">
            <a href="<?php echo e(route('news')); ?>" class="banner-btn" style="font-size:0.9rem;padding:0.7rem 2rem">
                جميع الأخبار <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if($latestActs->count()): ?>
<section class="section">
    <div class="section-inner">
        <div class="section-header">
            <span class="section-label">فعالياتنا</span>
            <h2 class="section-title">أبرز الأنشطة</h2>
            <div class="section-line"></div>
        </div>
        <div class="cards-grid">
            <?php $__currentLoopData = $latestActs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="card-img">
                    <?php if($act->image): ?>
                        <?php $imgAct = trim(explode(',', $act->image)[0]); ?>
                        <img src="<?php echo e(Storage::url($imgAct)); ?>" alt="<?php echo e($act->title); ?>">
                    <?php else: ?>
                        <i class="fas fa-star-and-crescent"></i>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-date">
                        <i class="fas fa-calendar" style="margin-right:4px"></i><?php echo e(\Carbon\Carbon::parse($act->activity_date)->format('d/m/Y')); ?>

                        <?php if($act->location): ?> &nbsp;|&nbsp; <i class="fas fa-map-marker-alt" style="margin-right:4px"></i><?php echo e($act->location); ?> <?php endif; ?>
                    </div>
                    <h3 class="card-title"><?php echo e($act->title); ?></h3>
                    <p class="card-desc"><?php echo e(Str::limit($act->description, 100)); ?></p>
                    <a href="<?php echo e(route('activities.show', $act->id)); ?>" class="card-link">التفاصيل <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="text-align:center;margin-top:2.5rem">
            <a href="<?php echo e(route('activities')); ?>" class="banner-btn" style="font-size:0.9rem;padding:0.7rem 2rem">
                جميع الأنشطة <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const hSlider = document.getElementById('homeSlider');
    const hDots = document.querySelectorAll('.banner-dot'); // Corrected selector

    function scrollHomeSlide(index) {
        const sw = hSlider.offsetWidth;
        // In RTL, we might need to use negative values or absolute positions
        const isRTL = document.dir === 'rtl';
        const targetPos = isRTL ? -(index * sw) : (index * sw);
        
        hSlider.scrollTo({ left: targetPos, behavior: 'smooth' });
    }

    function moveHomeSlider(dir) {
        const sw = hSlider.offsetWidth;
        const isRTL = document.dir === 'rtl';
        // In RTL, to go "next" (left), we subtract from scrollLeft if it's negative logic
        hSlider.scrollBy({ left: (isRTL ? -dir : dir) * sw, behavior: 'smooth' });
    }

    hSlider.addEventListener('scroll', () => {
        const sw = hSlider.offsetWidth;
        const scrollPos = Math.abs(hSlider.scrollLeft);
        const idx = Math.round(scrollPos / sw);
        
        hDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === idx);
        });
    });

    // Auto-advance
    let autoPlay = setInterval(() => moveHomeSlider(1), 5000);
    hSlider.addEventListener('mouseenter', () => clearInterval(autoPlay));
    hSlider.addEventListener('mouseleave', () => autoPlay = setInterval(() => moveHomeSlider(1), 5000));

    // Animation for Counters
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    }

    // Trigger animation when visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    if(counters.length > 0) {
        observer.observe(document.querySelector('.hero-stats'));
    }
</script>
<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/public/home.blade.php ENDPATH**/ ?>