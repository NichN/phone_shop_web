@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add New Brand</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('brand.store') }}" id="brandForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="brandName" class="form-label fw-bold">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="brandName" name="name" placeholder="" required>
                        </div>
                        <div class="col-md-6">
                            <label for="brandLogo" class="form-label fw-bold">Brand Logo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="brandLogo" name="logo" accept="image/*" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="brandDescription" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="brandDescription" name="description" rows="4" placeholder="Enter category description..."></textarea>
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
@include('Admin.brand.script');