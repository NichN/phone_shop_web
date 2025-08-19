@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Product List</h4>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-outline-primary bg-light" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>
            </button> 
            
        </div>   

        <div class="container no-print" style="z-index: 999999999999999 !important;">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3" style="min-width: auto !important;">
                        <thead>
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Name</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Brand</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Category</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Description</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                        <tbody>
                            {{-- Data to be loaded dynamically --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: black;">
                <h5 class="modal-title" id="addModalLabel">Add Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" action="{{ route('products.productstore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="add_prName" class="form-label fw-bold">Name<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="add_prName" name="name" placeholder="Enter product name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cat_id" class="form-label fw-bold">Category<span class="text-danger"> *</span></label>
                            <select class="form-select" id="cat_id" name="cat_id">
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="brand_id" class="form-label fw-bold">Brand<span class="text-danger"> *</span></label>
                            <select class="form-select" id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="add_prdescription" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="add_prdescription" name="description" placeholder="Enter description" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="saveBtn" class="btn btn-outline-primary px-4">
                            <i class="fas fa-save me-2"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="editForm">
                    @method('GET')
                    @csrf
                    <div class="row g-3 mb-4">
                         <input type="hidden" id="productId" nam="id">
                        <div class="col-md-6">
                            <label for="edit_prName" class="form-label fw-bold">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_prName" name="name" placeholder="Enter product name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_cat_id" class="form-label fw-bold">Category<span class="text-danger"> *</span></label>
                            <select class="form-select" id="edit_cat_id" name="cat_id">
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_brand_id" class="form-label fw-bold">Brand<span class="text-danger"> *</span></label>
                            <select class="form-select" id="edit_brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_description" name="description" placeholder="Enter description" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" class="btn btn-outline-primary px-4">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- View product --}}
@include('Admin.product.script')
