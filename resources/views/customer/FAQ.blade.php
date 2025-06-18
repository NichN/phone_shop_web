@extends('Layout.headerfooter')

@section('title', 'FAQ')

@section('header')
<div class="position-relative text-overlay-container">
    <div class="overlay-text position-absolute top-50 start-0 ms-4 text-secondary">
    </div>
</div>
@endsection

@section('content')
 {{-- <img src="{{ asset('image/bannerFAQ.png') }}"  alt="FAQ Banner" style="max-height: 400px; object-fit: cover; width:"> --}}
<div class="container my-5 py-4">
    <div class="text-center mb-5">
        <p class="lead"><strong>Find quick answers to common questions below</strong></p>
        <div class="mt-4">
            <input type="text" class="form-control rounded-pill w-75 mx-auto shadow-sm" placeholder="Search FAQs...">
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion shadow-sm" id="faqAccordion">
                @forelse($faqs as $index => $faq)
                    <div class="accordion-item border-top mb-3 rounded overflow-hidden">
                        <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                            <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaq{{ $faq->id }}" aria-expanded="false"
                                    aria-controls="collapseFaq{{ $faq->id }}">
                                <i class="bi bi-question-circle me-3 text-primary"></i>
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapseFaq{{ $faq->id }}" class="accordion-collapse collapse"
                             aria-labelledby="faqHeading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">No FAQs available at the moment.</p>
                @endforelse
            </div>

            <div class="card mt-5 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <h3 class="card-title mb-3">Still have questions?</h3>
                    <p class="card-text mb-4">Our customer service team is happy to help with any other inquiries you may have.</p>
                    <a href="/contactus" class="btn btn-primary px-4 py-2 rounded-pill">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .accordion-button {
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }

    .accordion-body {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    .card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
@endsection
