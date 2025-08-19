@extends('Layout.headerfooter')

@section('title', 'FAQ')

@section('header')
<div class="position-relative text-overlay-container">
    <div class="banner-container">
        <img src="{{ asset('image/banner2.png') }}" alt="FAQ Banner" class="img-fluid w-100" style="max-height: 300px; object-fit: cover;">
        <div class="banner-text position-absolute top-50 start-50 translate-middle text-center text-dark">
            <h4 class="fw-bold">Frequently Asked Questions</h4>
            <p class="lead">Find answers to common questions about our services</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container my-5 py-4">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h3 class="fw-bold mb-4">How can we help you?</h3>
            {{-- <div class="search-container mx-auto" style="max-width: 600px;">
                <input type="text" class="form-control rounded-pill shadow-sm px-4 py-3" placeholder="Search FAQs...">
            </div> --}}
        </div>
    </div>

    <div class="row g-3">
        @forelse($faqs as $faq)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-0 shadow-sm faq-card bg-light">
                    <div class="card-body p-4">
                        <div class="icon-container mb-3">
                            <i class="bi bi-question-circle-fill fs-2" style="color:#a3444f"></i>
                        </div>
                        <h5 class="card-title fw-bold">{{ $faq->question }}</h5>
                        <div class="card-text faq-answer mt-3">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">No FAQs available at the moment.</p>
            </div>
        @endforelse
    </div>

    <div class="row mt-5">
    <div class="col-12 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between bg-light p-4 rounded shadow-sm">
        <div class="mb-3 mb-md-0 me-md-4">
            <h5 class="fw-bold">Still have questions?</h5>
            <p class="fs-6 mb-0">Our customer service team is happy to help with any other inquiries you may have.</p>
        </div>
        <a href="/contactus" class="btn px-4 py-2" style="background-color: #d88c95; color:white;">Contact Us</a>
    </div>
</div>

</div>
@endsection

@section('styles')
<style>
    .banner-container {
        position: relative;
    }
    
    .banner-text {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .faq-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }
    
    .faq-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    .icon-container {
        width: 50px;
        height: 50px;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .contact-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
    }
    
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    
    .faq-answer {
        color: #6c757d;
        line-height: 1.6;
    }
</style>
@endsection