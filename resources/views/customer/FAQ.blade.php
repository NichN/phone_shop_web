@extends('Layout.headerfooter')

@section('title', 'FAQ')

@section('header')
<div class="position-relative text-overlay-container">
    {{-- <img src="{{ asset('picture/image') }}" class="img-fluid w-100 baner_img" alt="FAQ Banner"> --}}
    <div class="overlay-text position-absolute top-50 start-0 ms-4 text-secondary">
    </div>
</div>
@endsection

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">How Can We Help You?</h2>
    <p class="text-center text-muted mb-5">Browse through our most common questions or contact us for personalized assistance.</p>

    <div class="accordion hadow-sm" id="faqAccordion">
        <!-- General Questions Section -->
        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="generalQuestion1">
                <button class="accordion-button rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral1" aria-expanded="true" aria-controls="collapseGeneral1">
                    What products do you offer?
                </button>
            </h5>
            <div id="collapseGeneral1" class="accordion-collapse collapse show" aria-labelledby="generalQuestion1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We offer a wide range of electronics, including smartphones, laptops, tablets, and accessories to meet all your technology needs.
                </div>
            </div>
        </div>

        <!-- Shipping Question -->
        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="shippingQuestion1">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShipping1" aria-expanded="false" aria-controls="collapseShipping1">
                    How long does shipping take?
                </button>
            </h5>
            <div id="collapseShipping1" class="accordion-collapse collapse" aria-labelledby="shippingQuestion1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Shipping typically takes 3-5 business days within the country. For international orders, delivery may take 7-14 days.
                </div>
            </div>
        </div>

        <!-- Returns Question -->
        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="returnsQuestion1">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReturns1" aria-expanded="false" aria-controls="collapseReturns1">
                    What is your return policy?
                </button>
            </h5>
            <div id="collapseReturns1" class="accordion-collapse collapse" aria-labelledby="returnsQuestion1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Our return policy allows returns within 30 days of purchase for unused and undamaged products with the original receipt.
                </div>
            </div>
        </div>

        <!-- Additional FAQs -->
        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="faqQuestion1">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq1" aria-expanded="false" aria-controls="collapseFaq1">
                    How do I track my order?
                </button>
            </h5>
            <div id="collapseFaq1" class="accordion-collapse collapse" aria-labelledby="faqQuestion1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Once your order is shipped, you will receive a tracking number via email. Use this number on our website or courier's tracking system.
                </div>
            </div>
        </div>

        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="faqQuestion2">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq2" aria-expanded="false" aria-controls="collapseFaq2">
                    What payment methods do you accept?
                </button>
            </h5>
            <div id="collapseFaq2" class="accordion-collapse collapse" aria-labelledby="faqQuestion2" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    We accept various payment methods including credit cards, PayPal, and online banking.
                </div>
            </div>
        </div>

        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="faqQuestion3">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq3" aria-expanded="false" aria-controls="collapseFaq3">
                    Do you offer international shipping?
                </button>
            </h5>
            <div id="collapseFaq3" class="accordion-collapse collapse" aria-labelledby="faqQuestion3" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Yes, we ship internationally. Shipping fees and delivery times may vary depending on the destination.
                </div>
            </div>
        </div>

        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="faqQuestion4">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq4" aria-expanded="false" aria-controls="collapseFaq4">
                    Can I cancel or change my order?
                </button>
            </h5>
            <div id="collapseFaq4" class="accordion-collapse collapse" aria-labelledby="faqQuestion4" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can cancel or change your order within 24 hours of placing it. Contact our customer service team for assistance.
                </div>
            </div>
        </div>

        <div class="accordion-item border-0 mb-3">
            <h5 class="accordion-header" id="faqQuestion5">
                <button class="accordion-button collapsed rounded-pill shadow-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq5" aria-expanded="false" aria-controls="collapseFaq5">
                    How do I contact customer support?
                </button>
            </h5>
            <div id="collapseFaq5" class="accordion-collapse collapse" aria-labelledby="faqQuestion5" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    You can contact our support team via email at support@taymeng.com or call us at +123-456-7890.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
