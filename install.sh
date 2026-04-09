#!/bin/bash
# ============================================================
# سكريبت تثبيت مشروع دليل قبيلة مسونق
# ============================================================

echo ""
echo "╔═══════════════════════════════════════════════╗"
echo "║        تثبيت دليل قبيلة مسونق - Laravel      ║"
echo "╚═══════════════════════════════════════════════╝"
echo ""

# التحقق من PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP غير مثبت. قم بتثبيت PHP 8.1+ أولاً."
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo "✅ PHP $PHP_VERSION موجود"

# التحقق من Composer
if ! command -v composer &> /dev/null; then
    echo "❌ Composer غير مثبت."
    exit 1
fi
echo "✅ Composer موجود"

# إنشاء مشروع Laravel
echo ""
echo "📦 جاري إنشاء مشروع Laravel..."
composer create-project laravel/laravel masoung-app --quiet
cd masoung-app

# نسخ ملفات المشروع
echo "📁 جاري نسخ ملفات المشروع..."
cp -r ../masoung/app/Http/Controllers/* app/Http/Controllers/
cp -r ../masoung/app/Http/Middleware/* app/Http/Middleware/
cp -r ../masoung/resources/views/* resources/views/
cp -r ../masoung/routes/web.php routes/web.php
cp -r ../masoung/database/migrations/* database/migrations/
cp -r ../masoung/database/seeders/DatabaseSeeder.php database/seeders/

echo ""
echo "⚙️  إعداد ملف .env..."
cp .env.example .env

# طلب بيانات قاعدة البيانات
echo ""
read -p "اسم قاعدة البيانات [masoung_db]: " DB_NAME
DB_NAME=${DB_NAME:-masoung_db}

read -p "اسم مستخدم MySQL [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "كلمة مرور MySQL: " DB_PASS
echo ""

# تعديل .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=root/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASS/" .env

echo ""
echo "🔑 توليد مفتاح التطبيق..."
php artisan key:generate --quiet

echo ""
echo "🗄️  إنشاء الجداول وإضافة البيانات..."
php artisan migrate --seed --force

echo ""
echo "🔗 إنشاء رابط التخزين..."
php artisan storage:link --quiet

echo ""
echo "╔═══════════════════════════════════════════════╗"
echo "║              ✅ تم التثبيت بنجاح!             ║"
echo "╠═══════════════════════════════════════════════╣"
echo "║  شغّل الخادم:  php artisan serve              ║"
echo "║  الموقع:       http://localhost:8000           ║"
echo "║  الإدارة:      http://localhost:8000/admin-access ║"
echo "║  البريد:       admin@masoung.com               ║"
echo "║  كلمة المرور: Admin@2024                       ║"
echo "╚═══════════════════════════════════════════════╝"
echo ""
echo "⚠️  تذكر: غيّر كلمة مرور الادمن فور تشغيل المشروع!"
echo ""
