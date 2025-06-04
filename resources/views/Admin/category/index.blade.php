@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">Category List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('category.new') }}">
                <button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
            </a>
        </div>    
        <div class="container no-print" style="z-index: 999999999999999 !important;">
            <div>
                <div class="card-body">                                 
                    <div class="table-responsive"> 
                        <table class="table data-table mt-3" style="min-width: auto !important;">
                            <thead>
                                <tr>
                                    <th style="background-color: #70000e !important; color: white !important;">No</th>
                                    <th style="background-color: #70000e !important; color: white !important;">Picture</th>
                                    <th style="background-color: #70000e !important; color: white !important;">Name</th>
                                    <th style="background-color: #70000e !important; color: white !important;">Description</th>
                                    <th style="background-color: #70000e !important; color: white !important;">Created Date</th>
                                    <th style="background-color: #70000e !important; color: white !important;">Action</th>
                                </tr>
                            </thead>                                
                        </table>
                    </div>
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
                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="cateId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="Name">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" id="edit_description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" class="form-control" id="edit_photo" name="image" required>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('Admin.category.script')