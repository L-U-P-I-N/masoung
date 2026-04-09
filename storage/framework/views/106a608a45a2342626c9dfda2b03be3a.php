<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول الإدارة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold: #C9A84C; --gold-light: #E8C97A; --gold-dark: #9A7A2E;
            --dark: #0D1117; --dark2: #161B22; --dark3: #1C2128;
            --text: #E6EDF3; --text-muted: #8B949E; --border: rgba(201,168,76,0.25);
            --red: #ef4444;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Cairo',sans-serif;
            background: var(--dark); color: var(--text);
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:1.5rem;
        }
        body::before {
            content:''; position:fixed; inset:0;
            background: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(201,168,76,0.07) 0%, transparent 70%);
            pointer-events:none;
        }
        .login-box {
            background: var(--dark2); border: 1px solid var(--border);
            border-radius:20px; padding:3rem 2.5rem;
            width:100%; max-width:420px; position:relative;
        }
        .login-box::before {
            content:'';
            position:absolute; top:-1px; right:20%; left:20%;
            height:2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            border-radius:2px;
        }
        .login-icon {
            width:70px; height:70px; margin:0 auto 1.5rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius:18px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.8rem; color:var(--dark);
        }
        h1 {
            text-align:center; font-size:1.6rem; font-weight:700;
            margin-bottom:0.4rem;
        }
        .subtitle { text-align:center; color:var(--text-muted); font-size:0.88rem; margin-bottom:2rem; }
        .alert-error {
            background: rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3);
            color:#fca5a5; padding:0.8rem 1rem; border-radius:10px;
            font-size:0.88rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:0.5rem;
        }
        .alert-success {
            background: rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.3);
            color:#86efac; padding:0.8rem 1rem; border-radius:10px;
            font-size:0.88rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:0.5rem;
        }
        .form-group { margin-bottom:1.2rem; }
        label { display:block; font-size:0.85rem; font-weight:600; color:var(--text-muted); margin-bottom:0.5rem; }
        input {
            width:100%; background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; padding:0.75rem 1rem;
            color:var(--text); font-family:'Cairo',sans-serif; font-size:0.95rem;
            transition:border-color 0.2s; outline:none;
        }
        input:focus { border-color: var(--gold); }
        input.error-input { border-color: rgba(239,68,68,0.5); }
        .field-error { color:#fca5a5; font-size:0.8rem; margin-top:0.3rem; }
        .btn-login {
            width:100%; background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color:var(--dark); font-family:'Cairo',sans-serif; font-weight:700;
            font-size:1rem; padding:0.85rem; border:none; border-radius:10px;
            cursor:pointer; transition:all 0.3s; margin-top:0.5rem;
        }
        .btn-login:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(201,168,76,0.3); }
        .back-home {
            display:block; text-align:center; margin-top:1.5rem;
            color:var(--text-muted); text-decoration:none; font-size:0.85rem;
            transition:color 0.2s;
        }
        .back-home:hover { color:var(--gold); }
        .password-wrapper { position:relative; }
        .password-wrapper input { padding-left:2.8rem; }
        .toggle-password {
            position:absolute; left:0.8rem; top:50%; transform:translateY(-50%);
            background:none; border:none; color:var(--text-muted); cursor:pointer;
            font-size:1rem; padding:0.2rem; transition:color 0.2s;
        }
        .toggle-password:hover { color:var(--gold); }
    </style>
</head>
<body>
<div class="login-box">
    <div class="login-icon">⚜</div>
    <h1>لوحة الإدارة</h1>
    <p class="subtitle">قبيلة مسونق — منطقة مقيدة</p>

    <?php if(session('error')): ?>
    <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if(session('success')): ?>
    <div class="alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.login.post')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" placeholder="admin@example.com"
                value="<?php echo e(old('email')); ?>"
                class="<?php echo e($errors->has('email') ? 'error-input' : ''); ?>">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><i class="fas fa-exclamation-triangle" style="margin-left:4px"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="••••••••"
                    class="<?php echo e($errors->has('password') ? 'error-input' : ''); ?>">
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="field-error"><i class="fas fa-exclamation-triangle" style="margin-left:4px"></i><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            
            <div style="text-align:left;margin-top:0.6rem">
                <a href="<?php echo e(route('admin.password.request')); ?>" style="color:var(--text-muted);font-size:0.8rem;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-muted)'">نسيت كلمة المرور؟</a>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt" style="margin-left:0.5rem"></i>
            تسجيل الدخول
        </button>
    </form>

    <a href="<?php echo e(route('home')); ?>" class="back-home">
        <i class="fas fa-arrow-right" style="margin-left:0.4rem"></i>
        العودة للموقع الرئيسي
    </a>
</div>
<script>
function togglePass(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>
<?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/auth/login.blade.php ENDPATH**/ ?>