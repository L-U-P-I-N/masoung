<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير كلمة المرور</title>
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
        .login-box {
            background: var(--dark2); border: 1px solid var(--border);
            border-radius:20px; padding:3rem 2.5rem;
            width:100%; max-width:420px; position:relative;
        }
        .login-icon {
            width:70px; height:70px; margin:0 auto 1.5rem;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius:18px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.8rem; color:var(--dark);
        }
        h1 { text-align:center; font-size:1.6rem; font-weight:700; margin-bottom:0.4rem; }
        .subtitle { text-align:center; color:var(--text-muted); font-size:0.88rem; margin-bottom:2rem; }
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
            transition:border-color 0.2s; outline:none; text-align:center;
        }
        input:focus { border-color: var(--gold); }
        .btn-reset {
            width:100%; background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color:var(--dark); font-family:'Cairo',sans-serif; font-weight:700;
            font-size:1rem; padding:0.85rem; border:none; border-radius:10px;
            cursor:pointer; transition:all 0.3s; margin-top:0.5rem;
        }
        .btn-reset:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(201,168,76,0.3); }
        .code-input { letter-spacing: 1rem; font-size: 1.5rem; font-weight: 700; }
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
    <div class="login-icon"><i class="fas fa-check-double"></i></div>
    <h1>تفعيل الكلمة الجديدة</h1>
    <p class="subtitle">أدخل الرمز المرسل وكلمة المرور الجديدة</p>

    @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.password.update.reset') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="form-group">
            <label>رمز التحقق (6 أرقام)</label>
            <input type="text" name="code" class="code-input" placeholder="000000" maxlength="6" required autofocus>
            @error('code')<p style="color:#fca5a5; font-size:0.8rem; margin-top:0.3rem;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>كلمة المرور الجديدة</label>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="••••••••" required style="text-align:right; letter-spacing:normal;">
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<p style="color:#fca5a5; font-size:0.8rem; margin-top:0.3rem;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <div class="password-wrapper">
                <input type="password" name="password_confirmation" placeholder="••••••••" required style="text-align:right; letter-spacing:normal;">
                <button type="button" class="toggle-password" onclick="togglePass(this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        
        <button type="submit" class="btn-reset">
            <i class="fas fa-key" style="margin-left:0.5rem"></i>
            تحديث كلمة المرور
        </button>
    </form>
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
