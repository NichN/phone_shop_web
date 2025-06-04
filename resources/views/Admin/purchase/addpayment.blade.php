@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add Payment</h5>
            </div>
            <div class="card-body">
                <form id="addpaymentForm">
                    @csrf
                    @method('POST')
                    <div class="row mb-4">
                        <input type="hidden" id="purchase_id" value="{{ $purchase->id }}">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Reference no <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reference" name="reference_no" placeholder="Enter reference number" value="{{$purchase->reference_no}}" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" id="editsupplier_id" name="supplier_id" disabled>
                                 @foreach($suppliers as $supplier)
                                    <option value="" readonly>{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Grand total <span class="text-danger">*</span></label>
                            <input class="form-control" name="grand_total" id="editgrand_total" value="{{$purchase->Grand_total}}" disabled>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Remaining<span class="text-danger">*</span></label>
                            <input class="form-control" name="remaining" id="remaining" placeholder="" value="{{$purchase->balance}}" disabled>
                        </div>
                    </div>
                <div class="row mb-4">
                    <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Paid <span class="text-danger">*</span></label>
                            <input class="form-control" name="paid" id="paid1" placeholder="Enter your payment" required>
                        </div>
                    <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Balance <span class="text-danger">*</span></label>
                            <input class="form-control" name="balance" id="balance" placeholder="" required>
                        </div>
                        </div>
                        <input type="hidden" id="payment_st" name="payment_st">
                        <div class="d-flex justify-content-end pt-4">
                            <a href="{{ route('purchase.add') }}">
                                <button type="submit" class="btn btn-primary d-flex justify-content-center align-items-center">Done</button>
                            </a>
                        </div>
                    </form>    
                    </div>  
                </div>
            </div>
    @include('Admin.purchase.script');

