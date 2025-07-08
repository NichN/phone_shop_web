
@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
         <div class="flex justify-between">
            <h4 class="card-header">Product List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('pr_detail.add') }}">
                <button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
            </a>
        </div>    
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th>No</th>
                                {{-- <th>Image</th> --}}
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Warranty</th>
                                <th>Action</th>
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
            <div class="modal-header">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Edit Product</h5>
            </div>
            <div class="modal-body">
                <form id="editPr">
                     @method('GET')
                    @csrf
                    <div class="row mb-4">
                        <input type="hidden" id="pro_dt" name="id">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <select class="select2 js-states form-select" multiple="multiple" id="edit_ProName" name="name" required onchange="changeprduct(this)">
                                <option value="">Choose Name</option>
                                @foreach($product as $products)
                                <option value="{{ $products->id }}">{{ $products->name }}</option>
                                 @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Brand <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="edit_brandName" name="brandName" placeholder="" required onchange="localStorage.setItem('brandName', this.value)">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_Category" name="edit_categoryName" placeholder="" required onchange="localStorage.setItem('Category', this.value)">
                        </div>
                        <div class="col-md-2">
                            <label for="brandName" class="form-label fw-bold"> Cost Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="edit_costPrice" name="cost_price" placeholder="Enter Cost Price" required>
                        </div>
                        <div class="col-md-2">
                            <label for="brandName" class="form-label fw-bold"> Sell Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="edit_sellPrice" name="price" placeholder="Enter Sell Price" required>
                        </div>
                         <div class="col-md-2">
                            <label for="type" class="form-label fw-bold">State<span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="" disabled selected>Select State</option>
                                <option value="new">New</option>
                                <option value="old">Second Hand</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-md-3">
                            <label for="color" class="form-label fw-bold">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_color_id" name="color_id">
                                <option value="">Choose Color</option>
                                @foreach($color as $colors)
                                    <option value="{{ $colors->id }}">{{ $colors->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="color" class="form-label fw-bold">Size <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_size_id" name="size_id">
                                <option value="">Choose Size</option>
                                @foreach($size as $sizes)
                                    <option value="{{ $sizes->id }}">{{ $sizes->size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                        <label for="size_id" class="form-label fw-bold">
                            Warranty
                             <input type="text" name="warranty" id="edit_warranty" class="form-control" value="">
                        </label>
                    </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <label for="images" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="edit_images" name="images[]" multiple accept="image/*" required onchange="previewImages(this)">
                            <small class="form-text text-muted">You can select multiple images.</small>
                        </div>
                    </div>
                    <div id="imagePreview" class="d-flex flex-wrap gap-3 mt-3"></div>
                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="saveBtn2" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Save
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
      <div class="modal-header" style="background-color: #2e3b56;">
        <h5 class="mb-0 text-white" id="viewModalLabel"><i class="fas fa-tags me-2"></i></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
<!-- Modal -->
@include('Admin.productdetail.script')

