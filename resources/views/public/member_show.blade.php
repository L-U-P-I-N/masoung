@extends('layouts.public')

@section('title', $member->name . ' - Member Profile')

@section('content')

<div class="page-header" style="padding: 100px 2rem 40px;">
    <div style="text-align: center;">
        <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 2rem; overflow: hidden; border: 4px solid var(--gold); box-shadow: 0 10px 30px rgba(201,168,76,0.3); background: var(--dark3);">
            @if($member->photo)
                <img src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-user" style="font-size:3rem;color:var(--text-muted)"></i>
                </div>
            @endif
        </div>
        <h1 style="font-family:'Amiri',serif; font-size:clamp(2rem,5vw,3rem); color:var(--gold); margin-bottom:1rem;">{{ $member->name }}</h1>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
            @if($member->profession)
            <span style="display: inline-block; background: rgba(255,255,255,0.05); color: var(--text); padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; border: 1px solid var(--border);">
                <i class="fas fa-briefcase" style="margin-right: 0.5rem;"></i>{{ $member->profession }}
            </span>
            @endif
        </div>

    </div>
</div>

<section class="section">
    <div class="section-inner">
        <div class="member-detail">
            <!-- Single Card with All Content -->
            <div class="card">
                <div class="card-body">
                    
                    <!-- Contact Information Section -->
                    <h3 style="color: var(--gold); margin-bottom: 1.5rem; font-size: 1.3rem; font-family: 'Amiri', serif; text-align: center;">معلومات الاتصال</h3>
                    
                    <div style="text-align: center;">
                        @if($member->phone)
                        <div style="margin-bottom: 1rem;">
                            <strong style="color: var(--text);">الهاتف</strong><br>
                            @php
                                $phones = explode(',', $member->phone);
                                foreach($phones as $phone):
                                    $phone = trim($phone);
                            @endphp
                            <a href="tel:{{ $phone }}" style="color: var(--gold); text-decoration: none; display: block; margin-bottom: 0.5rem;">{{ $phone }}</a>
                            @endforeach
                        </div>
                        @endif
                        
                        @if($member->email)
                        <div style="margin-bottom: 1rem;">
                            <strong style="color: var(--text);">البريد الإلكتروني</strong><br>
                            <a href="mailto:{{ $member->email }}" style="color: var(--gold); text-decoration: none;">{{ $member->email }}</a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 1rem; justify-content: center; margin: 2rem 0;">
                        @if($member->phone)
                        <a href="tel:{{ $member->phone }}" class="btn btn-primary">
                            <i class="fas fa-phone"></i> اتصل الآن
                        </a>
                        @endif
                        @if($member->email)
                        <a href="mailto:{{ $member->email }}" class="btn btn-outline">
                            <i class="fas fa-envelope"></i> أرسل بريد
                        </a>
                        @endif
                    </div>
                    
                    <!-- About Section -->
                    @if($member->bio)
                    <div style="margin: 2rem 0;">
                        <h3 style="color: var(--gold); margin-bottom: 1.5rem; font-size: 1.3rem; font-family: 'Amiri', serif; text-align: center;">نبذة عن</h3>
                        <p style="color: var(--text); line-height: 1.8; font-size: 1.1rem; text-align: center;">{{ $member->bio }}</p>
                    </div>
                    @endif
                    
                    <!-- Member Details -->
                    <div style="text-align: center; padding: 1.5rem; background: rgba(201,168,76,0.05); border-radius: 8px; border: 1px solid rgba(201,168,76,0.1); margin-top: 2rem;">
                        <h4 style="color: var(--text); margin-bottom: 0.5rem;">موقع السكن</h4>
                        <p style="color: var(--text-muted); margin: 0;">{{ $member->location ?? 'غير محدد' }}</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--gold);
    color: var(--dark);
}

.btn-primary:hover {
    background: var(--gold-light);
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: var(--gold);
    border: 1px solid var(--gold);
}

.btn-outline:hover {
    background: var(--gold);
    color: var(--dark);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .member-detail {
        max-width: 100%;
    }
    
    .member-detail > div {
        grid-template-columns: 1fr !important;
    }
    
    .page-header {
        padding: 80px 2rem 30px !important;
    }
}
</style>

@endsection
