{{-- resources/views/public/news.blade.php --}}
@extends('layouts.public')
@section('title', 'الأخبار')
@section('content')

<div class="page-header">
    <h1>أخبار القبيلة</h1>
</div>

<section class="section">
    <div class="section-inner">
        @if($news->isEmpty())
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--border)"></i>
                لا توجد أخبار مضافة حتى الآن
            </div>
        @else
        <div class="cards-grid">
            @foreach($news as $item)
            <div class="card">
                <div class="card-img">
                    @php
                        $imageString = $item->image ?? '';
                        $imageArray = array_filter(array_map('trim', explode(',', $imageString)));
                        $firstImg = count($imageArray) > 0 ? $imageArray[0] : null;
                    @endphp
                    @if($firstImg)
                        <img src="{{ Storage::url($firstImg) }}" alt="{{ $item->title }}" style="width:100%;height:200px;object-fit:cover;border-radius:8px;">
                    @else
                        <i class="fas fa-newspaper"></i>
                    @endif
                </div>
                <div class="card-body">
                    <div class="card-date"><i class="fas fa-calendar-alt" style="margin-left:4px"></i>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</div>
                    <h3 class="card-title">{{ $item->title }}</h3>
                    <p class="card-desc">{{ Str::limit($item->excerpt, 110) }}</p>
                    <a href="{{ route('news.show', $item->id) }}" class="card-link">اقرأ المزيد <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:3rem">{{ $news->links() }}</div>
        @endif
    </div>
</section>
@endsection
