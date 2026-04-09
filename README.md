# دليل قبيلة مسونق — مشروع Laravel

## 🏛 نظرة عامة
موقع دليل إلكتروني كامل لقبيلة مسونق مبني بـ **Laravel 10/11**، يتضمن:
- **واجهة عامة** للزوار (عرض فقط — بدون تسجيل)
- **لوحة إدارة محمية** عبر رابط سري
- إدارة كاملة: أعضاء، أنشطة، أخبار، إعدادات القبيلة

---

## 📁 هيكل المشروع

```
masoung/
├── app/Http/
│   ├── Controllers/
│   │   ├── AuthController.php       ← تسجيل دخول الادمن
│   │   ├── AdminController.php      ← لوحة التحكم (CRUD كامل)
│   │   └── PublicController.php     ← الصفحات العامة
│   └── Middleware/
│       └── AdminAuthenticate.php    ← حماية مسارات الادمن
├── database/
│   ├── migrations/                  ← إنشاء جداول قاعدة البيانات
│   └── seeders/DatabaseSeeder.php   ← بيانات تجريبية
├── resources/views/
│   ├── layouts/
│   │   ├── public.blade.php         ← قالب الواجهة العامة
│   │   └── admin.blade.php          ← قالب لوحة الادمن
│   ├── auth/login.blade.php         ← صفحة تسجيل الدخول
│   ├── public/                      ← صفحات الموقع العام
│   └── admin/                       ← صفحات لوحة التحكم
└── routes/web.php                   ← جميع المسارات
```

---

## 🚀 خطوات التثبيت

### 1. تثبيت المتطلبات
```bash
# تأكد من وجود PHP 8.1+ و Composer
php --version
composer --version
```

### 2. إنشاء مشروع Laravel جديد أو نسخ الملفات
```bash
composer create-project laravel/laravel masoung
cd masoung
```
ثم انسخ جميع الملفات من هذا المشروع إلى مجلد Laravel.

### 3. إعداد قاعدة البيانات
افتح ملف `.env` وعدّل بيانات الاتصال:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=masoung_db
DB_USERNAME=root
DB_PASSWORD=كلمة_المرور
```

أنشئ قاعدة البيانات:
```sql
CREATE DATABASE masoung_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. تشغيل الـ Migrations والـ Seeder
```bash
php artisan migrate --seed
```

هذا سيُنشئ:
- جميع الجداول (tribe_settings, members, activities, news, admins)
- بيانات تجريبية
- حساب الادمن الافتراضي

### 5. إعداد رابط التخزين
```bash
php artisan storage:link
```

### 6. توليد مفتاح التطبيق
```bash
php artisan key:generate
```

### 7. تشغيل الخادم
```bash
php artisan serve
```

الموقع سيكون متاحاً على: `http://localhost:8000`

---

## 🔐 الوصول للوحة الإدارة

### الرابط السري (لا يظهر في الموقع):
```
http://localhost:8000/admin-access
```

### بيانات الادمن الافتراضية:
| الحقل | القيمة |
|-------|--------|
| البريد | admin@masoung.com |
| كلمة المرور | Admin@2024 |

> ⚠️ **مهم:** غيّر كلمة المرور فور تثبيت المشروع!

### تغيير كلمة المرور:
```bash
php artisan tinker
DB::table('admins')->where('email','admin@masoung.com')
   ->update(['password' => Hash::make('كلمةالمرورالجديدة')]);
```

---

## 📄 صفحات الموقع العام (للزوار)

| الصفحة | المسار |
|--------|--------|
| الرئيسية | `/` |
| عن القبيلة | `/about` |
| الأعضاء | `/members` |
| الأنشطة | `/activities` |
| الأخبار | `/news` |

---

## 🛠 لوحة الإدارة

| القسم | المسار |
|-------|--------|
| لوحة التحكم | `/admin/dashboard` |
| إدارة الأعضاء | `/admin/members` |
| إدارة الأنشطة | `/admin/activities` |
| إدارة الأخبار | `/admin/news` |
| الإعدادات | `/admin/settings` |

---

## 🗄 جداول قاعدة البيانات

### `tribe_settings` — إعدادات القبيلة
- tribe_name, tribe_description, founded_date, location
- contact_email, contact_phone, logo, cover_image

### `members` — الأعضاء
- name, position, phone, email, bio, photo
- is_active, sort_order

### `activities` — الأنشطة
- title, description, content, image
- activity_date, location, is_published

### `news` — الأخبار
- title, excerpt, content, image
- is_published, published_at

### `admins` — المديرون
- name, email, password

---

## 🎨 التصميم
- **الخط:** Cairo + Amiri
- **اللون الأساسي:** ذهبي `#C9A84C`
- **الخلفية:** داكن `#0D1117`
- **الاتجاه:** RTL (عربي)
- **التجاوب:** متوافق مع الجوال

---

## 📋 المتطلبات
- PHP 8.1+
- Laravel 10+
- MySQL 5.7+ أو MariaDB 10.3+
- Composer
- Node.js (اختياري للـ assets)

---

## 📞 الدعم الفني
لأي استفسار حول المشروع أو تخصيصه، تواصل مع المطور.
