@extends('layouts.public')
@section('title', 'الأنشطة')
@section('content')

<div class="page-header">
    <h1>أنشطة القبيلة</h1>
</div>

<section class="section">
    <div class="section-inner">
        @if($activities->isEmpty())
            <div style="text-align:center;padding:4rem;color:var(--text-muted)">
                <i class="fas fa-calendar-times" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--border)"></i>
                لا توجد أنشطة مضافة حتى الآن
            </div>
        @else
        <div class="cards-grid">
            @foreach($activities as $act)
            <div class="card">
                <div class="card-img">
                    @php
                        $imageString = $act->image ?? '';
                        $imageArray = array_filter(array_map('trim', explode(',', $imageString)));
                        $firstImg = count($imageArray) > 0 ? $imageArray[0] : null;
                    @endphp
                    @if($firstImg)
                        <img src="{{ Storage::url($firstImg) }}" alt="{{ $act->title }}" style="width:100%;height:200px;object-fit:cover;border-radius:8px;">
                    @else
                        <i class="fas fa-calendar-alt"></i>
                    @endif
                </div>
                <div class="card-body">
                    <div class="card-date">
                        <i class="fas fa-calendar" style="margin-left:4px"></i>{{ \Carbon\Carbon::parse($act->activity_date)->format('d/m/Y') }}
                        @if($act->location) &nbsp;·&nbsp; <i class="fas fa-map-marker-alt" style="margin-left:4px"></i>{{ $act->location }} @endif
                    </div>
                    <h3 class="card-title">{{ $act->title }}</h3>
                    <p class="card-desc">{{ Str::limit($act->description, 110) }}</p>
                    <a href="{{ route('activities.show', $act->id) }}" class="card-link">التفاصيل <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:3rem">{{ $activities->links() }}</div>
        @endif
    </div>
</section>

@endsection
