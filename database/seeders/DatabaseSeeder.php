<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إعدادات القبيلة الافتراضية
        DB::table('tribe_settings')->insert([
            'tribe_name'        => 'قبيلة مسونق',
            'tribe_description' => 'قبيلة مسونق إحدى القبائل العريقة ذات التاريخ العميق والموروث الثقافي الأصيل. تجمع هذه القبيلة بين الأصالة والحداثة، وتسعى دائمًا إلى تعزيز الوحدة والتماسك بين أبنائها.',
            'founded_date'      => '1900-01-01',
            'location'          => 'المنطقة الشرقية',
            'contact_email'     => 'info@masoung.com',
            'contact_phone'     => '+966500000000',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // أعضاء نموذجيون
        $members = [
            ['name' => 'الشيخ محمد بن عبدالله المسونقي',  'position' => 'رئيس القبيلة',     'phone' => '+966501111111', 'sort_order' => 1],
            ['name' => 'سالم بن خالد المسونقي',            'position' => 'نائب الرئيس',       'phone' => '+966502222222', 'sort_order' => 2],
            ['name' => 'عبدالرحمن بن سعد المسونقي',        'position' => 'أمين الصندوق',      'phone' => '+966503333333', 'sort_order' => 3],
            ['name' => 'فهد بن ناصر المسونقي',             'position' => 'مسؤول الأنشطة',    'phone' => '+966504444444', 'sort_order' => 4],
        ];

        foreach ($members as $m) {
            DB::table('members')->insert(array_merge($m, [
                'bio'        => 'عضو فاعل في قبيلة مسونق، يسهم في نهضة القبيلة وتطويرها.',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // أخبار نموذجية
        DB::table('news')->insert([
            [
                'title'        => 'اجتماع القبيلة السنوي لعام ١٤٤٦',
                'excerpt'      => 'عقدت قبيلة مسونق اجتماعها السنوي الكبير بحضور أعيان القبيلة وشيوخها.',
                'content'      => 'في جو يسوده الأخوة والمحبة، عقدت قبيلة مسونق اجتماعها السنوي الكبير بحضور نخبة من أعيان القبيلة وشيوخها ومثقفيها. تناول الاجتماع عدة محاور رئيسية أبرزها: تعزيز الروابط الاجتماعية بين أبناء القبيلة، ومناقشة المشاريع التنموية المقترحة للعام القادم، وتكريم المتفوقين من أبناء القبيلة في مختلف المجالات.',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'تكريم المتفوقين من أبناء القبيلة',
                'excerpt'      => 'أقامت قبيلة مسونق حفل تكريم لأبنائها المتفوقين دراسياً وعلى مستوى العمل.',
                'content'      => 'أحتضنت قاعة الاحتفالات الكبرى حفل تكريم رائع لأبناء قبيلة مسونق المتفوقين في الدراسة والعمل والإبداع. وقد شهد الحفل تكريم ٣٠ متفوقاً في مختلف المجالات، وسط فرحة كبيرة من ذويهم وأهلهم.',
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // أنشطة نموذجية
        DB::table('activities')->insert([
            [
                'title'         => 'رحلة التراث والتاريخ',
                'description'   => 'رحلة استكشافية لمواقع التراث التاريخية للقبيلة.',
                'content'       => 'نظمت قبيلة مسونق رحلة تراثية استكشافية إلى المواقع التاريخية للقبيلة، شارك فيها أبناء القبيلة من مختلف الأعمار. هدفت الرحلة إلى تعريف الأجيال الجديدة بجذورهم وتاريخهم العريق.',
                'activity_date' => now()->subDays(10)->format('Y-m-d'),
                'location'      => 'المنطقة الشرقية',
                'is_published'  => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'title'         => 'بطولة الفروسية الأولى',
                'description'   => 'بطولة الفروسية التقليدية لأبناء قبيلة مسونق.',
                'content'       => 'أقامت قبيلة مسونق بطولتها الأولى للفروسية التقليدية في إطار الحفاظ على الموروث الثقافي الأصيل. تنافس في البطولة عدد كبير من الفرسان من أبناء القبيلة، وفاز بالمركز الأول الفارس سعد بن محمد المسونقي.',
                'activity_date' => now()->addDays(7)->format('Y-m-d'),
                'location'      => 'ساحة القبيلة الكبرى',
                'is_published'  => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);

        // حساب الادمن الافتراضي
        DB::table('admins')->insert([
            'name'       => 'مدير النظام',
            'email'      => 'admin@masoung.com',
            'password'   => Hash::make('Admin@2024'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
