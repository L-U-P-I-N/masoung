<!DOCTYPE html>
<html dir="rtl">
<head>
    <style>
        body { font-family: 'Tahoma', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #C9A84C; }
        .header { background-color: #0D1117; color: #C9A84C; padding: 30px; text-align: center; }
        .content { padding: 40px; text-align: center; color: #333333; line-height: 1.6; }
        .code { background-color: #f9f9f9; border: 2px dashed #C9A84C; color: #0D1117; font-size: 32px; font-weight: bold; padding: 20px; margin: 30px 0; border-radius: 8px; letter-spacing: 5px; }
        .footer { background-color: #f4f4f4; color: #777777; padding: 20px; text-align: center; font-size: 12px; }
        .btn { background-color: #C9A84C; color: #0D1117; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">قبيلة مسونق</h1>
        </div>
        <div class="content">
            <h2>طلب استعادة كلمة المرور</h2>
            <p>لقد تلقينا طلباً لإعادة تعيين كلمة مرور حساب الإدارة الخاص بك. يرجى استخدام رمز التحقق التالي لإتمام العملية:</p>
            <div class="code">{{ $code }}</div>
            <p>هذا الرمز صالح لمدة 15 دقيقة فقط. إذا لم تكن أنت من طلب هذا التغيير، فيرجى تجاهل هذا البريد.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} قبيلة مسونق. جميع الحقوق محفوظة.
        </div>
    </div>
</body>
</html>
