

<?php $__env->startSection('title', 'الأعضاء'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <h1>أعضاء القبيلة</h1>
</div>

<section class="section">
    <div class="section-inner">
        <!-- Search Field -->
        <div style="margin-bottom: 2rem;">
            <form method="GET" action="<?php echo e(route('members')); ?>" style="display: flex; gap: 1rem; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?php echo e($query ?? ''); ?>" 
                        placeholder="بحث" 
                        style="width: 100%; padding: 0.8rem 1rem 0.8rem 3rem; border: 1px solid var(--border); border-radius: 8px; background: var(--dark2); color: var(--text); font-size: 1rem; outline: none; transition: all 0.3s;"
                    >
                    <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                </div>
                <?php if($query ?? false): ?>
                <a href="<?php echo e(route('members')); ?>" style="padding: 0.8rem 1.5rem; background: var(--dark3); color: var(--text); border: 1px solid var(--border); border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                    <i class="fas fa-times"></i> مسح
                </a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if($members->isEmpty()): ?>
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-users" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--border)"></i>
                <?php echo e($query ? 'لا توجد نتائج' : 'لا يوجد أعضاء بعد'); ?>

            </div>
        <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem">
            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('members.show', $member->id)); ?>" class="card" style="text-align:center;text-decoration:none;color:inherit">
                <div style="padding:2rem 2rem 1rem">
                    <div style="width:100px;height:100px;border-radius:50%;margin:0 auto 1rem;overflow:hidden;border:3px solid var(--gold);background:var(--dark3);display:flex;align-items:center;justify-content:center">
                        <?php if($member->photo): ?>
                            <img src="<?php echo e(Storage::url($member->photo)); ?>" alt="<?php echo e($member->name); ?>" style="width:100%;height:100%;object-fit:cover">
                        <?php else: ?>
                            <i class="fas fa-user" style="font-size:2.5rem;color:var(--text-muted)"></i>
                        <?php endif; ?>
                    </div>
                    <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:0.4rem"><?php echo e($member->name); ?></h3>
                    <?php if($member->bio): ?>
                    <p style="font-size:0.88rem;color:var(--text-muted);line-height:1.7;margin-bottom:1rem"><?php echo e(Str::limit($member->bio, 90)); ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/public/members.blade.php ENDPATH**/ ?>