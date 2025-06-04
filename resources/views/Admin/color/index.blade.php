
@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Color List</h4>
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
                                <th>Name</th>
                                <th>Code</th>
                                <th>Date</th>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Color</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="colorForm" action="{{ route('color.colorstore') }}"  method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="colorName" class="form-label fw-bold">Color Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="colorName" name="name" placeholder="Enter color name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="colorCode" class="form-label fw-bold">Color Code <span class="text-danger">*</span></label>
                            <input type="color" class="form-control form-control-color" id="colorCode" name="code" required>
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
{{-- edit form --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Color</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editColorForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="ColorId" name="id">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="editColorName" class="form-label fw-bold">Color Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" placeholder="Enter color name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editColorCode" class="form-label fw-bold">Color Code <span class="text-danger">*</span></label>
                            <input type="color" class="form-control form-control-color" id="edit_code" name="code" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end border-top pt-4">
                        <button type="submit" id="updateBtn" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('Admin.color.script')

