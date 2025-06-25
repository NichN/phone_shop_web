@include('Admin.component.sidebar');
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                <th>No</th>
                                <th>image</th>
                                <th>name</th>
                                <th>Image Type</th>
                                <th>Action</th>
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
                <form id="imageForm" action="{{ route('photo.store') }}"  method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="supplierName" class="form-label fw-bold">Image Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="image_Name" name="name" placeholder="Enter Supplier name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="img_type" class="form-label fw-bold">Image Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="img_type" name="img_type" required>
                                <option value="">Select Image Type</option>
                                <option value="Homepage Banner1">Homepage Banner1</option>
                                <option value="Homepage Banner2">Homepage Banner2</option>
                                <option value="Homepage Banner3">Homepage Banner3</option>
                                <option value="Homepage Banner4">Homepage Banner4</option>
                                <option value="Homepage Banner5">Homepage Banner5</option>
                                <option value="bottom Banner">bottom Banner</option>
                                <option value="Homepage box1">Homepage box1</option>
                                <option value="Homepage box2">Homepage box2</option>
                                <option value="About us Banner">About us Banner</option>
                                <option value="Contact us Banner">Contact us Banner</option>
                            </select>
                        </div>
                        <br>
                        <div class="col-md-12 mt-3">
                            <label for="suppler_add" class="form-label fw-bold">Image<span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_path" name="file_path" required>
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
@include('Admin.image.script')