<?php $__env->startSection('title', 'تسجيل عضو جديد - قبيلة مسونق'); ?>

<?php $__env->startSection('content'); ?>
<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <i class="fas fa-user-plus"></i>
            <h1>طلب انضمام للقبيلة</h1>
            <p>يرجى ملء البيانات التالية بدقة للانضمام إلى دليل قبيلة مسونق</p>
        </div>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; padding:1rem; border-radius:12px; margin-bottom:1.5rem;">
            <ul style="margin:0; padding-right:1.5rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
        
        <?php endif; ?>

        <form action="<?php echo e(route('member.register.post')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div style="display:none;">
                <input type="text" name="website_verification_code" tabindex="-1" autocomplete="off">
            </div>
            
            <div class="form-group">
                <label>الاسم الكامل <span style="color:var(--red)">*</span></label>
                <input type="text" name="name" placeholder="أدخل اسمك الثلاثي أو الرباعي" required>
            </div>

            <div class="form-group">
                <label>الصفة / اللقب</label>
                <input type="text" name="position" placeholder="مثال: من الأعيان، عضو متطوع">
            </div>

            <div class="form-group">
                <label>المهنة</label>
                <input type="text" name="profession" placeholder="مثال: مهندس، طالب، تاجر">
            </div>

            <div class="form-group">
                <label>الدولة <span style="color:var(--red)">*</span></label>
                <input type="text" name="country" id="country-input" list="country-list" placeholder="ابحث أو اكتب اسم الدولة..." required oninput="updatePhoneValidation()">
                <datalist id="country-list">
                    <!-- JS will populate this -->
                </datalist>
            </div>

            <div class="form-group">
                <label>المحافظة <span style="color:var(--red)">*</span></label>
                <input type="text" name="province" required placeholder="مثال: بغداد، صنعاء، الرياض">
            </div>

            <div class="form-group">
                <label>المدينة / المنطقة <span style="color:var(--red)">*</span></label>
                <input type="text" name="city" required placeholder="مثال: الكرادة، حدة، العليا">
            </div>

            <div class="form-group" id="phone-container">
                <label>رقم الهاتف <span id="phone-hint" style="color:var(--text-muted); font-size: 0.75rem;">(أدخل الرقم المحلي)</span></label>
                <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                    <div id="active-code" style="display:none;">+967</div>
                    <input type="text" name="phone[]" class="phone-input" placeholder="7XXXXXXXX" style="flex:1" required>
                    <button type="button" class="btn" style="background:var(--dark3); color:var(--gold); border:1px solid var(--border); border-radius:10px; padding:0 1rem;" onclick="addPhoneField()">+</button>
                </div>
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني (اختياري)</label>
                <input type="email" name="email" placeholder="example@mail.com">
            </div>

            <div class="form-group">
                <label>نبذة مختصرة</label>
                <textarea name="bio" rows="3" placeholder="اكتب نبذة قصيرة عن نفسك..."></textarea>
            </div>

            <div class="form-group">
                <label>الصورة الشخصية</label>
                <div class="file-upload-wrapper" onclick="document.getElementById('photo-input').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p id="file-name">انقر هنا لاختيار صورة</p>
                    <input type="file" id="photo-input" name="photo" hidden accept="image/*" onchange="updateFileName(this)">
                </div>
            </div>

            <div style="margin-top:1rem;">
                <button type="submit" class="btn btn-primary" style="width:100%;">إرسال البيانات للمراجعة</button>
                <p style="text-align:center; margin-top:1rem; color:var(--text-muted); font-size:0.9rem;">سيتم مراجعة طلبك من قبل إدارة القبيلة وتفعيله قريباً</p>
            </div>
        </form>
    </div>
</div>

<style>
    .register-container { padding: 4rem 1rem; display: flex; justify-content: center; min-height: calc(100vh - 200px); }
    .register-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 600px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
    .register-header { text-align: center; margin-bottom: 2.5rem; }
    .register-header i { font-size: 3rem; color: var(--gold); margin-bottom: 1rem; }
    .register-header h1 { font-size: 1.8rem; color: #fff; margin-bottom: 0.5rem; }
    .register-header p { color: var(--text-muted); }
    
    form { display: flex; flex-direction: column; gap: 1.5rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.6rem; }
    .form-group label { color: #fff; font-size: 0.95rem; font-weight: 500; }
    .form-group input, .form-group select, .form-group textarea { background: var(--dark3); border: 1px solid var(--border); border-radius: 12px; padding: 0.9rem 1.2rem; color: #fff; font-size: 1rem; transition: 0.3s; width: 100%; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--gold); outline: none; background: #2a2a2a; }
    
    .file-upload-wrapper { border: 2px dashed var(--border); border-radius: 12px; padding: 2rem; text-align: center; cursor: pointer; transition: 0.3s; background: var(--dark3); }
    .file-upload-wrapper:hover { border-color: var(--gold); background: #2a2a2a; }
    .file-upload-wrapper i { font-size: 2.5rem; color: var(--gold); margin-bottom: 1rem; }
    .file-upload-wrapper p { color: var(--text-muted); margin: 0; }
    
    .btn-primary { background: linear-gradient(135deg, var(--gold), #c5a059); color: var(--dark1); font-weight: 700; border: none; border-radius: 12px; padding: 1rem; cursor: pointer; transition: 0.3s; font-size: 1.1rem; }
    .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); }
</style>

<script>
    const countryData = {
        'اليمن': { code: '+967', len: 9 },
        'العراق': { code: '+964', len: 10 },
        'السعودية': { code: '+966', len: 9 },
        'الإمارات': { code: '+971', len: 9 },
        'عمان': { code: '+968', len: 8 },
        'الكويت': { code: '+965', len: 8 },
        'قطر': { code: '+974', len: 8 },
        'البحرين': { code: '+973', len: 8 },
        'مصر': { code: '+20', len: 10 },
        'الأردن': { code: '+962', len: 9 },
        'سوريا': { code: '+963', len: 9 },
        'لبنان': { code: '+961', len: 8 },
        'فلسطين': { code: '+970', len: 9 },
        'تونس': { code: '+216', len: 8 },
        'المغرب': { code: '+212', len: 9 },
        'الجزائر': { code: '+213', len: 9 },
        'ليبيا': { code: '+218', len: 9 },
        'السودان': { code: '+249', len: 9 },
        'تركيا': { code: '+90', len: 10 },
        'أمريكا': { code: '+1', len: 10 },
        'كندا': { code: '+1', len: 10 },
        'بريطانيا': { code: '+44', len: 10 },
        'ألمانيا': { code: '+49', len: 11 },
        'فرنسا': { code: '+33', len: 9 },
        'إيطاليا': { code: '+39', len: 10 },
        'روسيا': { code: '+7', len: 10 },
        'الصين': { code: '+86', len: 11 },
        'اليابان': { code: '+81', len: 10 },
        'الهند': { code: '+91', len: 10 },
        'باكستان': { code: '+92', len: 10 },
        'إندونيسيا': { code: '+62', len: 10 },
        'ماليزيا': { code: '+60', len: 9 }
    };

    function updateFileName(input) {
        if (input.files[0]) {
            document.getElementById('file-name').textContent = "تم اختيار: " + input.files[0].name;
            document.getElementById('file-name').style.color = "var(--gold)";
        }
    }

    function populateCountryList() {
        const list = document.getElementById('country-list');
        Object.keys(countryData).forEach(country => {
            const option = document.createElement('option');
            option.value = country;
            list.appendChild(option);
        });
    }

    let activePrefix = '+967';
    let activeLen = 9;

    function updatePhoneValidation() {
        const input = document.getElementById('country-input');
        const val = input.value.trim();
        const config = countryData[val] || { code: '+', len: '7,15' };
        
        activePrefix = config.code;
        activeLen = config.len;
        
        document.getElementById('active-code').textContent = activePrefix;
        document.getElementById('phone-hint').textContent = config.len === '7,15' ? '(رقم الهاتف الدولي)' : `(يجب أن يكون الرقم ${activeLen} خانات)`;
        
        const phones = document.querySelectorAll('.phone-input');
        phones.forEach(p => {
            const pattern = activeLen === '7,15' ? '[0-9]{7,15}' : `[0-9]{${activeLen}}`;
            p.pattern = pattern;
            p.placeholder = activeLen === '7,15' ? 'رقم الهاتف' : 'X'.repeat(activeLen);
        });
    }

    function addPhoneField() {
        const container = document.getElementById('phone-container');
        const div = document.createElement('div');
        div.style.display = 'flex'; div.style.gap = '0.5rem'; div.style.marginBottom = '0.5rem';
        const pattern = activeLen === '7,15' ? '[0-9]{7,15}' : `[0-9]{${activeLen}}`;
        const placeholder = activeLen === '7,15' ? 'رقم الهاتف' : 'X'.repeat(activeLen);
        
    div.innerHTML = `
        <div class="active-code-display" style="display:none;">${activePrefix}</div>
        <input type="tel" name="phone[]" class="phone-input" placeholder="${placeholder}" style="direction:ltr; flex:1" required pattern="${pattern}">
        <button type="button" class="btn" style="background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); border-radius:10px; padding:0 0.8rem;" onclick="this.parentElement.remove()">×</button>
    `;
        container.appendChild(div);
    }

    document.addEventListener('DOMContentLoaded', () => {
        populateCountryList();
        updatePhoneValidation();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/members/register.blade.php ENDPATH**/ ?>