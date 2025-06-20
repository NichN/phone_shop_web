@extends('Layout.headerfooter')

@section('title', 'Contact Us')

@section('header')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
<!-- Image and Overlay Container -->
<div class="text-overlay-container">
  <img src="{{ asset('image/contactus3.jpg') }}" class="baner_img" alt="contact Banner">
  <div class="border-frame">
    <div class="overlay-text">
      <h1 class="fw-bold">Contact Us</h1>
      <p>We value your feedback and are here to assist you<br> with any questions or concerns.</p>
    </div>
  </div>
</div>
  
@endsection
@section('content')
<div class="container py-4">
  <div class="row justify-content-center g-3"> <!-- added g-3 for gutter spacing -->
    
    <div class="col-lg-3 col-md-4 col-sm-6">
      <a href="https://www.google.com/maps/@11.5720626,104.906753,14z/data=!4m2!10m1!1e1?hl=en&entry=ttu&g_ep=EgoyMDI1MDYxMS4wIKXMDSoASAFQAw%3D%3D" 
         target="_blank" 
         style="text-decoration: none; color: inherit;">
        <div class="text-center p-4 border rounded shadow">
          <i class="fa-solid fa-location-arrow icon fs-2"></i>
          <h4 class="fw-bold mt-3">Location</h4>
        </div>
      </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
      <a href="mailto:taymeng@gmail.com" style="text-decoration: none; color: inherit;">
        <div class="bg-secondary text-center p-4 border rounded shadow">
          <i class="fa-solid fa-envelope icon text-white fs-2"></i>
          <h4 class="fw-bold text-white mt-3">Email</h4>
        </div>
      </a>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6">
      <a href="tel:+85578640241" style="text-decoration: none; color: inherit;">
        <div class="text-center p-4 border rounded shadow">
          <i class="fa-solid fa-phone icon fs-2"></i>
          <h4 class="fw-bold mt-3">Phone</h4>
        </div>
      </a>
    </div>

  </div>
</div>

<div class="container mt-4">
  <div class="row">
    <div class="col">
      <h4 class="fw-normal text-center fw-bold">Send Us a Message</h4>
      <form id="contactForm" class="mt-5">
        <div class="row">
          <div class="col">
            <label class="fw-bold">Your Name</label>
            <input type="text" class="form-control border-secondary" placeholder="Enter Your Name" name="Name" id="name" required>
          </div>
          <div class="col">
            <label class="fw-bold">Phone Number</label>
            <input type="text" class="form-control border-secondary" placeholder="Enter Phone Number" name="Phone" id="phone">
          </div>
          <div class="mb-3 mt-3">
            <label for="email" class="form-label fw-bold">Email address</label>
            <input type="email" class="form-control border-secondary" id="email" aria-describedby="emailHelp" placeholder="Enter Your Email" name="email" required>
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="message" class="fw-bold">Message:</label>
          <textarea class="form-control border-secondary" rows="5" id="message" name="text" required></textarea>
        </div>
        <button type="submit" class="btn btn-secondary" id="submitBtn">Send Message</button>
        <div id="formFeedback" class="mt-2" style="display: none;"></div>
      </form>
    </div>
    <div class="col">
      <h4 class="fw-normal text-center fw-bold">Location</h4>
      <p class="fw-bold">78 Krala Haom Kong St. (118), Phnom Penh</p>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125069.37724563181!2d104.86133282261211!3d11.593516216161756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109513a28530555%3A0x283e659a32b64e83!2sTay%20Meng%20Mobile%20Center!5e0!3m2!1sen!2skh!4v1737611391553!5m2!1sen!2skh" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submitBtn');
    const formFeedback = document.getElementById('formFeedback');
    
    // Store original button text
    const originalBtnText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

    // Get form data
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        message: document.getElementById('message').value
    };

    // Basic sanitization
    for (let key in formData) {
        formData[key] = formData[key]
            .replace(/<script.*?>.*?<\/script>/gi, '')
            .replace(/[<>]/g, '');
    }

    // Format the Telegram message
    const telegramMessage = `Hello! You got a new message from: ({{ config('app.url') }})\n
Name: ${formData.name}
Email: ${formData.email}
Phone: ${formData.phone}\n
Message:\n${formData.message}`;

    const telegramBotToken = '8108484660:AAFfEtec51wHSAJfHso1BTT6X9_H5YfcMIo';
    const telegramChatId = '@ksaranauniyear4';
    const telegramUrl = `https://api.telegram.org/bot${telegramBotToken}/sendMessage?chat_id=${telegramChatId}&text=${encodeURIComponent(telegramMessage)}`;

    // Send to Telegram
    fetch(telegramUrl, {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.ok) {
            // Show success message
            formFeedback.style.display = 'block';
            formFeedback.innerHTML = '<div class="alert alert-success">Message sent successfully!</div>';
            document.getElementById('contactForm').reset();
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                formFeedback.style.display = 'none';
            }, 5000);
        } else {
            throw new Error('Telegram API error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        formFeedback.style.display = 'block';
        formFeedback.innerHTML = '<div class="alert alert-danger">Error sending message. Please try again.</div>';
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});
</script>

@endsection