@extends('layouts.public')
@section('title', $item->title)
@section('content')

<div class="page-header">
    <h1>{{ $item->title }}</h1>
</div>

<section class="section">
    <div class="section-inner" style="max-width:860px">
        @if($item->image)
        @php
            $images = array_filter(array_map('trim', explode(',', $item->image)));
        @endphp
        <div class="banner-container" id="newsBanner">
            <div class="banner-slider" id="mainSlider">
                @foreach($images as $index => $image)
                    <div class="banner-item" id="slide-{{ $index }}">
                        <img src="{{ Storage::url($image) }}" alt="{{ $item->title }}">
                    </div>
                @endforeach
            </div>
            
            @if(count($images) > 1)
                <div class="banner-nav prev" onclick="moveSlider(-1)"><i class="fas fa-chevron-right"></i></div>
                <div class="banner-nav next" onclick="moveSlider(1)"><i class="fas fa-chevron-left"></i></div>
                
                <div class="banner-dots">
                    @foreach($images as $index => $image)
                        <div class="banner-dot {{ $index === 0 ? 'active' : '' }}" onclick="scrollToSlide({{ $index }})"></div>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-bottom:2rem">
            <span style="background:rgba(201,168,76,0.1);color:var(--gold);padding:0.4rem 1rem;border-radius:8px;font-size:0.85rem">
                <i class="fas fa-calendar" style="margin-left:0.4rem"></i>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}
            </span>
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2.5rem;line-height:2;font-size:1.05rem">
            {!! nl2br(e($item->content)) !!}
        </div>

        <div style="margin-top:2rem">
            <a href="{{ route('news') }}" style="color:var(--gold);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem">
                <i class="fas fa-arrow-right"></i> العودة للأخبار
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const slider = document.getElementById('mainSlider');
    const dots = document.querySelectorAll('.banner-dot');

    function scrollToSlide(index) {
        const slideWidth = slider.offsetWidth;
        slider.scrollTo({
            left: index * slideWidth,
            behavior: 'smooth'
        });
    }

    function moveSlider(direction) {
        const slideWidth = slider.offsetWidth;
        slider.scrollBy({
            left: direction * slideWidth,
            behavior: 'smooth'
        });
    }

    slider.addEventListener('scroll', () => {
        const slideWidth = slider.offsetWidth;
        const index = Math.round(Math.abs(slider.scrollLeft) / slideWidth);
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    });
</script>
@endpush
@endsection
