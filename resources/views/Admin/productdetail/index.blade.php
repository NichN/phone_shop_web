
@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
         <div class="flex items-center justify-between bg- px-4  rounded-md shadow-sm" style="background-color: aliceblue; padding: 10px;" >
            <div class="d-flex align-items-center justify-content-between w-100">
                <h4 class="text-lg font-semibold text-gray-800 mb-0">Product Variant List</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('pr_detail.add') }}">
                        <button class="btn btn-outline-primary bg-light">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </button>
                    </a>
                    <button class="btn btn-outline-success bg-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <button type="reset" id="resetFilter" class="btn btn-outline-danger bg-light">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
        </div>  
         
        <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-heade">
                <h5 class="modal-title text-dark" id="filterModalLabel">Filter Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
            <form id="filterForm" method="GET" action="{{ route('pr_detail.index') }}">
                <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Product Name</label>
                        <select name="product_id" id="filterProduct" class="form-select">
                            <option value="">Select Product</option>
                            @foreach($product as $products)
                                <option value="{{ $products->id }}">{{ $products->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" id="filterBrand" class="form-select">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="cat_id" id="filterCategory" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
 
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Image</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Name</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Brand</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Category</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Type</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Color</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Size</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Stock</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Price</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Warranty</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Featured</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-light">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="editPr" method="GET" enctype="multipart/form-data">
                    @method('POST')
                    @csrf

                    <input type="hidden" id="pro_dt" name="id">

                    <!-- Name and Brand -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_ProName1" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <select class="select2 js-states form-control" id="edit_ProName" name="name" required disabled>
                                <option value="">Choose Name</option>
                                @foreach($product as $products)
                                    <option value="{{ $products->id }}">{{ $products->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_brandName" class="form-label fw-bold">Brand <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_brandName" name="brandName" required disabled>
                        </div>
                    </div>

                    <!-- Category and Cost Price -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_Category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_Category" name="edit_categoryName" required disabled onchange="localStorage.setItem('Category', this.value)">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_costPrice" class="form-label fw-bold">Cost Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_costPrice" name="cost_price" placeholder="e.g. 1200" required>
                        </div>
                    </div>

                    <!-- Sell Price and State -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_sellPrice" class="form-label fw-bold">Sell Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_sellPrice" name="price" placeholder="e.g. 1500" disabled required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_type" class="form-label fw-bold">State <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="">Select State</option>
                                <option value="new">New</option>
                                <option value="Second hand">Second Hand</option>
                            </select>
                        </div>
                    </div>

                    <!-- Color and Size -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_color_id" class="form-label fw-bold">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_color_id" name="color_id" required>
                                <option value="">Choose Color</option>
                                @foreach($color as $colors)
                                    <option value="{{ $colors->id }}">{{ $colors->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_size_id" class="form-label fw-bold">Size <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_size_id" name="size_id" required>
                                <option value="">Choose Size</option>
                                @foreach($size as $sizes)
                                    <option value="{{ $sizes->id }}">{{ $sizes->size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Warranty and Product Images -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_warranty" class="form-label fw-bold">Warranty (Months) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_warranty" name="warranty" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_images" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="edit_images" name="images[]" multiple accept="image/*"  onchange="previewImages(this)">
                            <small class="form-text text-muted">You can select multiple images.</small>
                        </div>
                    </div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="d-flex flex-wrap gap-3 mt-3"></div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="saveBtn2" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Edit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="viewDetailModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="mb-0 text-black" id="viewModalLabel"><i class="fas fa-tags me-2"></i></h5>
        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
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
              </div>
              <br><br>
              <div>
                <h5 class="mb-4">Warranty</h5>
                <div id="warrantyOptions" class="d-flex gap-2"></div>
              </div>
              <br><br>
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
<!-- Modal -->
@include('Admin.productdetail.script')


