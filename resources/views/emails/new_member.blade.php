<x-mail::message>
# عضو جديد بانتظار الموافقة

مرحباً بك، لقد قام عضو جديد بتسجيل بياناته عبر الموقع الإلكتروني.

**تفاصيل العضو:**
- **الاسم:** {{ $member->name }}
- **المنصب:** {{ $member->position ?? 'لا يوجد' }}
- **المهنة:** {{ $member->profession ?? 'لا يوجد' }}
- **الدولة:** {{ $member->country }}
- **المدينة:** {{ $member->province }} - {{ $member->city }}
- **رقم الهاتف:** {{ $member->phone }}

للموافقة الفورية على هذا العضو دون الحاجة لتسجيل دخولك إلى لوحة التحكم، يمكنك النقر على الزر أدناه:

<x-mail::button :url="URL::signedRoute('admin.members.approve.signed', ['id' => $member->id])">
الموافقة الفورية على العضو
</x-mail::button>

إذا كنت ترغب في مراجعة كافة البيانات أو رفض الطلب، يمكنك الدخول إلى لوحة التحكم:

<x-mail::button :url="route('admin.members')">
لوحة تحكم الأعضاء
</x-mail::button>

شكراً لك,<br>
{{ config('app.name') }}
</x-mail::message>
