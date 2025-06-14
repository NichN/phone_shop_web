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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Add Product
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

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2e3b56;">
                <h5 class="modal-title" id="addModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" action="{{ route('products.productstore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="add_prName" class="form-label fw-bold">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_prName" name="name" placeholder="Enter product name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cat_id" class="col-sm control-label">Category</label>
                            <select class="form-select" id="cat_id" name="cat_id">
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="brand_id" class="col-sm control-label">Brand</label>
                            <select class="form-select" id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="add_prdescription" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="add_prdescription" name="description" placeholder="Enter description" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="saveBtn" class="btn btn-primary px-4">
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
                            <label for="edit_cat_id" class="col-sm control-label">Category</label>
                            <select class="form-select" id="edit_cat_id" name="cat_id">
                                <option value="">Select Category</option>
                                @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="edit_brand_id" class="col-sm control-label">Brand</label>
                            <select class="form-select" id="edit_brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label fw-bold">Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_description" name="description" placeholder="Enter description" rows="4" required></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- View product --}}
<div class="modal fade" id="viewDetailModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2e3b56;">
        <h5 class="mb-0 text-white" id="viewModalLabel"><i class="fas fa-tags me-2"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container my-3">
          <div class="row mt-3">
            <div class="col-md-6">
            <div class="main-image-container border p-3 text-center">
                <img id="mainProductImage" class="img-fluid" style="height: 300px;" alt="Main Product Image">
            </div>
            <div class="row thumbnail mt-3" style="border-color:1px solid blue;" id="thumbnailGallery"></div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="mb-0" id="product_name" style="color:#2e3b56; font-size:25px;"></h2>
                <p class="mb-0" id="brand_name"></p>
              </div>
              <h3 class="text-danger mb-4" id="product_price" style="color:red; font-size:25px;"></><strong></strong></h3>

              <div class="choose-color mb-4">
                <h5 class="mb-4">Color</h5>
                <div id="colorOptions" class="d-flex gap-2 mb-2"></div>
              </div>
              <div class="choose-storage">
                <h5 class="mb-4">Storage</h5>
                <div id="sizeOptions" class="d-flex gap-2"></div>
              </div><br><br>
              <div class="check_stock p-3 border rounded bg-light shadow-sm">
                <p class="mb-2 fw-bold text-dark">
                    Stock <span class="text-danger">*</span>
                </p>
                <div id="stock_qty" class="d-flex align-items-center gap-2">
                    <span class="badge bg-success"></span>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('Admin.product.script')
