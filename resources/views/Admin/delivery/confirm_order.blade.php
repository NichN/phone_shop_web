@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="w3-container">
            <div class="w3-card-4 w3-padding w3-margin-top" style="max-width:1100px;margin:auto;border-radius: 10px;">
                <form id="deliveryVerifyForm" method="POST" action="{{ route('delivery_option.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="w3-section">
                        <a href="/deliveries" style="color: blue;text-decoration:none;"><b>&larr; Back</b></a>
                        <div class="w3-row-padding mt-3">
                            <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
                            <div class="w3-half">
                                <label><strong>Order Number</strong></label>
                                <input class="w3-input w3-border" type="text" id="order_number" name="order_number" required readonly value="{{ $order->order_number }}" style="border-radius: 10px;">
                            </div>
                            <div class="w3-half">
                                <label><strong>Delivery Date</strong></label>
                                <input class="w3-input w3-border" type="text" id="delivery_date" name="delivery_date" required readonly value="{{ $order->delivery_date }}" style="border-radius: 10px;">
                            </div>
                        </div>
                        <div class="w3-margin-top">
                            <label><strong>Recipient Name</strong></label>
                            <input class="w3-input w3-border" type="text" id="recipient_name" name="customer_name" style="border-radius: 10px;" required value="{{ $order->customer_name }}">
                        </div>
                    </div>

                    <div class="w3-section">
                        @if(isset($delivery) && $delivery->delivery_image)
                            <label><strong>Uploaded Delivery Image</strong></label>
                            <div class="w3-card w3-padding w3-center w3-light-grey">
                                @if($data['delivery_image'])
                                    <img src="{{ $data['delivery_image'] }}" alt="Delivery Proof" style="max-height:300px;width:400px;">
                                @endif
                            </div>
                        @else
                            <div class="w3-margin-bottom">
                                <label><strong>Upload Delivery Verification Image</strong> <span style="color: red;">*</span></label>
                                <div class="w3-panel w3-padding">
                                    <input class="w3-input" type="file" id="delivery_image" name="delivery_image" accept="image/*" required>
                                </div>
                                <div id="imagePreviewSection" class="w3-hide">
                                    <label>Image Preview</label>
                                    <div class="w3-card w3-padding w3-center w3-light-grey">
                                        <img id="deliveryImagePreview" src="#" alt="Delivery Proof Preview" style="max-height:300px;width:400px;" />
                                        <div class="w3-margin-top">
                                            <button type="button" class="w3-button w3-red w3-small" onclick="removeImage()">
                                                <i class="fa fa-trash"></i> Remove Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="w3-section">
                        <label><strong>Delivery Notes (Optional)</strong></label>
                        <textarea class="w3-input w3-border" id="delivery_notes" name="delivery_notes" rows="3" style="border-radius: 10px;">{{ $delivery->delivery_notes ?? '' }}</textarea>
                    </div>

                    @if(!isset($delivery) || !$delivery->delivery_image)
                        <div class="w3-section w3-pale-yellow w3-padding">
                            <label>
                                <input class="w3-check" type="checkbox" id="delivery_confirmed" required>
                                I confirm that this delivery has been completed successfully and the recipient has received the goods in good condition.
                            </label>
                        </div>
                    @endif

                    <div class="w3-section w3-center">
                        @if(isset($delivery) && $delivery->delivery_image)
                            <button type="button" class="w3-button w3-green w3-round-large w3-padding" disabled>
                                <i class="fa fa-check-circle"></i> Delivery Already Confirmed
                            </button>
                        @else
                            <button type="submit" class="w3-button w3-blue w3-round-large w3-padding">
                                <i class="fa fa-check-circle"></i> Confirm Delivery
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('Admin.delivery.script')

<style>
#deliveryImagePreview {
    border: 1px dashed #ccc;
    padding: 5px;
    background-color: white;
}
.w3-card {
    transition: all 0.3s ease;
}
</style>

<script>
document.getElementById('delivery_image')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('deliveryImagePreview').src = e.target.result;
            document.getElementById('imagePreviewSection').classList.remove('w3-hide');
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    document.getElementById('delivery_image').value = '';
    document.getElementById('imagePreviewSection').classList.add('w3-hide');
}
</script>
