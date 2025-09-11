@include('Admin.component.sidebar');
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Image List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>
            </button> 
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
                                <th style ="background-color: #2e3b56 !important; color: white !important;">Title</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Image Type</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Description</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Default</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Create form --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageForm" action="{{ route('photo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <!-- Image Name -->
                        <div class="col-md-6">
                            <label for="image_Name" class="form-label fw-bold">Image Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="image_Name" name="name" placeholder="Enter image name" required>
                        </div>

                        <!-- Image Type -->
                        <div class="col-md-6">
                            <label for="img_type" class="form-label fw-bold">Image Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="img_type" name="img_type" required>
                                <option value="">Select Image Type</option>
                                <option value="Homepage Banner1">Homepage Banner</option>
                                <option value="Homepage box1">Homepage box1</option>
                                <option value="Homepage box2">Homepage box2</option>
                                <option value="About us Banner">About us Banner</option>
                                <option value="Contact us Banner">Contact us Banner</option>
                            </select>
                        </div>

                        <!-- Image Title -->
                        <div class="col-md-6 mt-3">
                            <label for="image_title" class="form-label fw-bold">Title</label>
                            <input type="text" class="form-control" id="image_title" name="title" placeholder="Enter image title">
                        </div>

                        <!-- Image Description -->
                        <div class="col-md-12 mt-3">
                            <label for="image_description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="image_description" name="description" rows="3" placeholder="Enter image description"></textarea>
                        </div>

                       
                            <div class="col-md-12 mt-3">
                                <label class="form-label fw-bold">Product (optional)</label>
                                <select name="product_item_id" id="create_product_item_id" class="form-select select2-create">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                        <!-- Image Upload -->
                        <div class="col-md-12 mt-3">
                            <label for="file_path" class="form-label fw-bold">Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_path" name="file_path" required>
                        </div>
                    </div>

                    <!-- Default Status -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label fw-bold">Set as Default <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_default" id="defaultYes" value="1">
                            <label class="form-check-label" for="defaultYes">Active</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_default" id="defaultNo" value="0" checked>
                            <label class="form-check-label" for="defaultNo">Not Active</label>
                        </div>
                    </div>

                    <!-- Submit -->
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
// edit
<!-- Edit Modal -->
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editImageModalLabel">Edit Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editImageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_image_id">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Image Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image Type</label>
                            <select class="form-select" id="edit_img_type" name="img_type" required>
                                <option value="">Select Image Type</option>
                                <option value="Homepage Banner1">Homepage Banner</option>
                                <option value="Homepage box1">Homepage box1</option>
                                <option value="Homepage box2">Homepage box2</option>
                                <option value="About us Banner">About us Banner</option>
                                <option value="Contact us Banner">Contact us Banner</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" placeholder="Enter image title">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Enter image description"></textarea>
                        </div>
                        <!-- Product Item Dropdown (Edit) -->
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold">Link to Product (optional)</label>
                            <select name="product_item_id" id="edit_product_item_id" class="form-select select2-edit">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-12 mt-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" id="edit_file_path" name="file_path">
                            <div id="currentImage" class="mt-2"></div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Set as Default</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_default" id="edit_defaultYes" value="1">
                                <label class="form-check-label" for="edit_defaultYes">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_default" id="edit_defaultNo" value="0">
                                <label class="form-check-label" for="edit_defaultNo">Not Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('Admin.image.script')