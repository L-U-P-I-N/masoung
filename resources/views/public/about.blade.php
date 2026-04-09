@extends('layouts.public')

@section('title', 'عن القبيلة')

@section('content')

<div class="page-header">
    <h1>عن قبيلة مسونق</h1>
</div>

<section class="section">
    <div class="section-inner" style="max-width:900px">

        @if($settings->cover_image ?? false)
        <div style="border-radius:20px;overflow:hidden;margin-bottom:3rem;border:1px solid var(--border)">
            <img src="{{ Storage::url($settings->cover_image) }}" alt="قبيلة مسونق" style="width:100%;height:400px;object-fit:cover">
        </div>
        @endif

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:3rem">
            @if($settings->founded_date ?? false)
            <div style="background:var(--dark2);border:1px solid var(--border);border-radius:14px;padding:1.5rem;text-align:center">
                <div style="font-size:2.5rem;color:var(--gold);margin-bottom:0.5rem">⚜</div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.3rem">تأسيس القبيلة</div>
                <div style="font-size:1.3rem;font-weight:700;color:var(--gold)">{{ \Carbon\Carbon::parse($settings->founded_date)->format('Y') }}</div>
            </div>
            @endif
            @if($settings->location ?? false)
            <div style="background:var(--dark2);border:1px solid var(--border);border-radius:14px;padding:1.5rem;text-align:center">
                <div style="font-size:2.5rem;color:var(--gold);margin-bottom:0.5rem"><i class="fas fa-map-marker-alt"></i></div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.3rem">الموقع</div>
                <div style="font-size:1.1rem;font-weight:700">{{ $settings->location }}</div>
            </div>
            @endif
            @if($settings->contact_phone ?? false)
            <div style="background:var(--dark2);border:1px solid var(--border);border-radius:14px;padding:1.5rem;text-align:center">
                <div style="font-size:2.5rem;color:var(--gold);margin-bottom:0.5rem"><i class="fas fa-phone"></i></div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.3rem">التواصل</div>
                <div style="font-size:1rem;font-weight:600;direction:ltr">{{ $settings->contact_phone }}</div>
            </div>
            @endif
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2.5rem">
            <h2 style="font-family:'Amiri',serif;font-size:1.8rem;color:var(--gold);margin-bottom:1.5rem;border-bottom:1px solid var(--border);padding-bottom:1rem">
                <i class="fas fa-book-open" style="margin-left:0.7rem;font-size:1.4rem"></i>
                نبذة عن القبيلة
            </h2>
            <p style="line-height:2.1;font-size:1.05rem;color:var(--text)">
                {{ $settings->tribe_description ?? 'قبيلة مسونق إحدى القبائل العريقة ذات التاريخ العميق والموروث الثقافي الأصيل.' }}
            </p>
        </div>

    </div>
</section>

@endsection
