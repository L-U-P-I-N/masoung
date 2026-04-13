@push('styles')
<style>
    /* Lightbox Styles */
    .lightbox {
        position: fixed; inset: 0; z-index: 3000;
        background: rgba(13,17,23,0.95);
        display: none; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.3s;
    }
    .lightbox.open { display: flex; opacity: 1; }
    .lightbox-content {
        position: relative; max-width: 90%; max-height: 85%;
        display: flex; flex-direction: column; align-items: center;
    }
    .lightbox-img {
        max-width: 100%; max-height: 80vh; border-radius: 10px;
        box-shadow: 0 0 40px rgba(0,0,0,0.5); object-fit: contain;
    }
    .lightbox-nav {
        position: absolute; top: 50%; width: 100%;
        display: flex; justify-content: space-between; transform: translateY(-50%);
        padding: 0 2rem; pointer-events: none;
    }
    .lightbox-btn {
        background: rgba(255,255,255,0.1); color: white; border: none;
        width: 50px; height: 50px; border-radius: 50%; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; transition: all 0.2s; pointer-events: auto;
    }
    .lightbox-btn:hover { background: var(--gold); color: var(--dark); }
    .lightbox-close {
        position: absolute; top: -50px; left: 0;
        color: white; font-size: 1.5rem; cursor: pointer;
    }
    .lightbox-actions {
        margin-top: 1.5rem; display: flex; gap: 1rem;
    }
    .btn-dl {
        background: var(--gold); color: var(--dark); padding: 0.6rem 1.5rem;
        border-radius: 8px; text-decoration: none; font-weight: 700;
        display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;
    }

    /* Gallery Styles */
    .gallery-title { font-family:'Amiri',serif; font-size:1.8rem; margin:3rem 0 1.5rem; color:var(--gold); display:flex; align-items:center; gap:0.8rem; }
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.2rem; }
    .gallery-item {
        background: var(--dark2); border: 1px solid var(--border);
        border-radius: 12px; overflow: hidden; position: relative;
    }
    .gallery-item img { width: 100%; height: 140px; object-fit: cover; cursor: pointer; transition: transform 0.3s; }
    .gallery-item:hover img { transform: scale(1.05); }
    .gallery-dl-btn {
        width: 100%; background: var(--dark3); border: none; border-top: 1px solid var(--border);
        color: var(--text); padding: 0.6rem; cursor: pointer; font-size: 0.8rem; font-weight: 600;
        display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.2s;
        text-decoration: none;
    }
    .gallery-dl-btn:hover { background: var(--gold); color: var(--dark); }
</style>
@endpush

<div class="page-header">
    <div class="breadcrumb"><a href="{{ route('home') }}">الرئيسية</a> / <a href="{{ route('activities') }}">الأنشطة</a></div>
    <h1>{{ $activity->title }}</h1>
</div>

<section class="section">
    <div class="section-inner" style="max-width:860px">
        @if($activity->image)
        @php
            $images = array_filter(array_map('trim', explode(',', $activity->image)));
        @endphp
        <div class="banner-container" id="activityBanner">
            <div class="banner-slider" id="mainSlider">
                @foreach($images as $index => $image)
                    <div class="banner-item" id="slide-{{ $index }}">
                        <img src="{{ Storage::url($image) }}" alt="{{ $activity->title }}" onclick="openLightbox({{ $index }})" style="cursor:zoom-in">
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
                <i class="fas fa-calendar" style="margin-left:0.4rem"></i>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d/m/Y') }}
            </span>
            @if($activity->location)
            <span style="background:rgba(201,168,76,0.1);color:var(--gold);padding:0.4rem 1rem;border-radius:8px;font-size:0.85rem">
                <i class="fas fa-map-marker-alt" style="margin-left:0.4rem"></i>{{ $activity->location }}
            </span>
            @endif
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:2.5rem;line-height:2;font-size:1.05rem;color:rgba(255,255,255,0.9)">
            {!! nl2br(e($activity->content ?? $activity->description)) !!}
        </div>

        @if(isset($images) && count($images) > 0)
        <h2 class="gallery-title"><i class="fas fa-images"></i> معرض الصور</h2>
        <div class="gallery-grid">
            @foreach($images as $index => $image)
                <div class="gallery-item">
                    <img src="{{ Storage::url($image) }}" alt="صورة {{ $index+1 }}" onclick="openLightbox({{ $index }})">
                    <a href="{{ route('download.image', ['path' => $image]) }}" class="gallery-dl-btn">
                        <i class="fas fa-download"></i> تحميل
                    </a>
                </div>
            @endforeach
        </div>
        @endif

        <div style="margin-top:3rem; border-top:1px solid var(--border); padding-top:2rem">
            <a href="{{ route('activities') }}" style="color:var(--gold);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem">
                <i class="fas fa-arrow-right"></i> العودة للأنشطة
            </a>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()"><i class="fas fa-times"></i></button>
        <img id="lightboxImg" class="lightbox-img" src="" alt="Full view">
        
        <div class="lightbox-nav">
            <button class="lightbox-btn" onclick="navLightbox(-1)"><i class="fas fa-chevron-right"></i></button>
            <button class="lightbox-btn" onclick="navLightbox(1)"><i class="fas fa-chevron-left"></i></button>
        </div>

        <div class="lightbox-actions">
            <a id="lightboxDl" href="#" class="btn-dl">
                <i class="fas fa-download"></i> تحميل هذه الصورة
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const slider = document.getElementById('mainSlider');
    const dots = document.querySelectorAll('.banner-dot');
    const images = @json(array_values($images ?? []));
    const storageUrl = "{{ Storage::url('') }}";
    const downloadRoute = "{{ route('download.image') }}";
    
    let currentIdx = 0;

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

    if(slider) {
        slider.addEventListener('scroll', () => {
            const slideWidth = slider.offsetWidth;
            const index = Math.round(Math.abs(slider.scrollLeft) / slideWidth);
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        });
    }

    /* Lightbox logic */
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lightboxImg');
    const lbDl = document.getElementById('lightboxDl');

    function openLightbox(index) {
        currentIdx = index;
        updateLightbox();
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lb.classList.remove('open');
        document.body.style.overflow = 'auto';
    }

    function navLightbox(dir) {
        currentIdx = (currentIdx + dir + images.length) % images.length;
        updateLightbox();
    }

    function updateLightbox() {
        const path = images[currentIdx];
        const fullUrl = path.startsWith('http') ? path : (storageUrl + path);
        lbImg.src = fullUrl;
        lbDl.href = `${downloadRoute}?path=${encodeURIComponent(path)}`;
    }

    // Close on click outside or ESC
    lb.addEventListener('click', (e) => {
        if(e.target === lb) closeLightbox();
    });
    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') closeLightbox();
        if(lb.classList.contains('open')) {
            if(e.key === 'ArrowRight') navLightbox(-1);
            if(e.key === 'ArrowLeft') navLightbox(1);
        }
    });
</script>
@endpush
