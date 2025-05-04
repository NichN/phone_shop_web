@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add New Category</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('category.store') }}" id="categoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="categoryName" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="categoryName" name="name" placeholder="" required>
                            <div class="invalid-feedback">Please provide a category name</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="categoryDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="4" placeholder="Enter category description..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="categoryImage" class="form-label fw-bold">Category Image</label>
                        <div class="file-upload-wrapper">
                            <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
                            <small class="text-muted">Max file size: 2MB (JPEG, PNG, WEBP)</small>
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@include('Admin.category.script');