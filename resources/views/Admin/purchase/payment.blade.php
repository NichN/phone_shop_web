@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add Payment</h5>
            </div>
            <div class="card-body">
                {{-- {{ route('pr_detail.store') }} --}}
                <form id="paymentForm" action="{{route('purchase.storepayment')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Reference no <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reference" name="reference_no" placeholder="Enter reference number" readonly>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                <option value="">Choose Supplier</option>
                                @foreach($suppleir as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Grand total <span class="text-danger">*</span></label>
                            <input class="form-control" id="grand_total" name="grand_total" placeholder="" required>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Paid <span class="text-danger">*</span></label>
                            <input class="form-control" id="paid" name="paid" placeholder="Enter your pay" required>
                        </div>
                    </div>
                <div class="row mb-4">
                    <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Balance <span class="text-danger">*</span></label>
                            <input class="form-control" id="balance" name="balance" placeholder="" required>
                        </div>
                </div>
                <input type="hidden" id="payment_st" name="payment_st">
                <div class="d-flex justify-content-end pt-4">
                    <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center">Submit</button>
                </div>
            </form>    
            </div>  
        </div>
    </div>
@include('Admin.purchase.script');

