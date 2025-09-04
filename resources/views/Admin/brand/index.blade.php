
@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Brand List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('brand.new') }}">
                <button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
            </a>
        </div>    
        <div class="container no-print">
            <div class="card-body">                                 
                <div class="table-responsive"> 
                    <table class="table data-table mt-3">
                        <thead style="text-align:center;">
                            <tr>
                                <th style="background-color: #2e3b56 !important; color: white !important;">No</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Logo</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Name</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Description</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Created Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Action</th>
                            </tr>
                        </thead>                                
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- edit form --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="brandId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name">
                    </div>
                    <div class="mb-4">
                        <label for="brandLogo" class="form-label fw-bold">Brand Logo </label>
                        <div class="file-upload-wrapper">
                            <input type="file" class="form-control" id="brandLogo" name="logo" accept="image/*">
                            <small class="text-muted">Max file size: 2MB (JPEG, PNG, WEBP)</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('Admin.brand.script')

