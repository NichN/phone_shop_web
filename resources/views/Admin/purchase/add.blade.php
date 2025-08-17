@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add Purchases</h5>
            </div>
            <div class="card-body">
                {{-- {{ route('pr_detail.store') }} --}}
                <form id="purForm" action="{{route('purchase.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <input type="hidden" name="size_id" id="size_id">
                         <input type="hidden" name="purchase_id" id="purchase_id">
                        {{-- <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Reference no <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="refer_no" name="reference" placeholder="Enter reference number">
                        </div> --}}
                        {{-- <div class="col-md-5">
                        <label for="brandName" class="form-label fw-bold">Supplier <span class="text-danger">*</span></label>
                        <select class="form-select" id="supplier_id" name="supplier_id" required>
                            <option value="">Choose Supplier</option>
                            @foreach($suppleir as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <label for="brandName" class="form-label fw-bold"> Purchase Product <span class="text-danger">*</span></label>
                            <select class="select2 js-states form-select" multiple="multiple" id="pur_product" name="product_name" placeholder="" required onchange="changeProduct()">
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                    <div class="col-md-2">
                            <label for="brandName" class="form-label fw-bold"> Quantity <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="qty" name="quantity" placeholder="0" required>
                    </div>
                    <input type="hidden" name="cost_price" id="size_id">
                    <div class="col-md-3">
                            <label for="brandName" class="form-label fw-bold"> Unit Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="unitprice" name="subtotal" required>
                    </div>
                    <div class="col-md-2">
                            <label for="colorName" class="form-label fw-bold"> Color <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="color" name="color" required>
                    </div>
                    <div class="d-flex justify-content-end pt-4">
                        <button type="submit" id="saveBtn" class="btn btn-primary d-flex justify-content-center align-items-center"
                            style="height: 40px; width: 40px; border-radius: 50%;">
                        <i class="fas fa-plus"></i>
                    </button>
                    </div>
                </div>
             </form>    
            </div>  
        </div>
        <div class="container no-print" style="z-index: 999999999999999 !important;">
            <div class="card-body">                                 
            <div class="table-responsive"> 
                <table class="table data-table purchase mt-3" style="min-width: auto !important;" id="purchase-table">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Product</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Color</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Size</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">QTY</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Unit Cost</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Subtotal</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead> 
                        <tfoot>
                                <tr>
                                    <th colspan="6" style="background-color:light;"></th>
                                    <th style="background-color: #2e3b56 !important; color: white !important;" id="totalPaymentFooter"></th>
                                    <th></th>
                                </tr>
                        </tfoot>                               
                    </table>
                    <tbody></tbody>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end pt-4">
        <a href="{{ route('purchase.payment') }}">
            <button type="submit" id="saveAll" class="btn btn-primary d-flex justify-content-center align-items-center">Next</button>
        </a>
    </div>
    </div>
</div>
@include('Admin.purchase.script');

