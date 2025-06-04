@include('Admin.component.sidebar');
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Supplier List</h4>
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
                                <th>Address</Address></th>
                                <th>Phone</th>
                                <th>Email</th>
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
                <h5 class="modal-title" id="addModalLabel">Add Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="supplierForm" action="{{ route('supplier.store') }}"  method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="supplierName" class="form-label fw-bold">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_Name" name="name" placeholder="Enter Supplier name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="suppler_add" class="form-label fw-bold">Addres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_address" name="address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="supplierphone" class="form-label fw-bold">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_phone" name="phone" placeholder="Enter phone number" required>
                        </div>
                        <div class="col-md-6">
                            <label for="suppler_email" class="form-label fw-bold">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_email" name="email" required>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editsupplierForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="supplierId" name="id">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="supplierName" class="form-label fw-bold">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_Name" name="name" placeholder="Enter Supplier name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="suppler_add" class="form-label fw-bold">Addres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_address" name="address" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="supplierphone" class="form-label fw-bold">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" placeholder="Enter phone number" required>
                        </div>
                        <div class="col-md-6">
                            <label for="suppler_email" class="form-label fw-bold">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_email" name="email" required>
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
@include('Admin.supplier.script')