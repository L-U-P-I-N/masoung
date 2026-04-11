<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 20px; }
        .container { background-color: #fff; max-width: 600px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #c9a84c; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 30px; line-height: 1.6; }
        .info-box { background-color: #f9f9f9; border: 1px solid #eee; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .footer { background-color: #eee; padding: 15px; text-align: center; font-size: 12px; color: #777; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #c9a84c; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>تنبيه أمان: تسجيل دخول جديد</h2>
        </div>
        <div class="content">
            <p>مرحباً،</p>
            <p>نود إبلاغك بأنه تم تسجيل دخول جديد إلى لوحة إدارة <strong><?php echo e($tribe_name); ?></strong>.</p>
            
            <div class="info-box">
                <p><strong>الاسم:</strong> <?php echo e($admin_name); ?></p>
                <p><strong>البريد الإلكتروني:</strong> <?php echo e($admin_email); ?></p>
                <p><strong>عنوان IP:</strong> <?php echo e($ip_address); ?></p>
                <p><strong>التوقيت:</strong> <?php echo e($timestamp); ?></p>
            </div>

            <p>إذا كنت أنت من قام بهذا الإجراء، فلا داعي للقلق. أما إذا لم تقم بذلك، يرجى تغيير كلمة المرور فوراً ومراجعة سجل النشاطات.</p>
            
            <center>
                <a href="<?php echo e(route('admin.login')); ?>" class="btn">الانتقال إلى لوحة التحكم</a>
            </center>
        </div>
        <div class="footer">
            هذا البريد تم إرساله تلقائياً من نظام إدارة قبيلة مسونق.
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\PHP\masoung-laravel\masoung\resources\views/emails/login_alert.blade.php ENDPATH**/ ?>