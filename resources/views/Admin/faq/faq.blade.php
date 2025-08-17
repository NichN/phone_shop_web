@include('Admin.component.sidebar');
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div>
    <button class="w3-button w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
    <div class="w3-main">
        <div class="flex justify-between">
            <h4 class="card-header">FAQ List</h4>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addModal">
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
                                <th style="background-color: #2e3b56 !important; color: white !important;">Date</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Qustion</th>
                                <th style="background-color: #2e3b56 !important; color: white !important;">Answer</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create FAQ</h5>
                {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>

            <div class="modal-body">
                <form id="faqForm" action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="question" class="form-label fw-bold">Question <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="question" 
                                name="question" 
                                rows="3" 
                                placeholder="Enter the question here..." 
                                required></textarea>
                        </div>

                        <div class="col-12">
                            <label for="answer" class="form-label fw-bold">Answer <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="answer" 
                                name="answer" 
                                rows="4" 
                                placeholder="Provide a detailed answer..." 
                                required></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 border-top pt-3">
                        <button type="submit" id="saveBtn" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="addModalLabel">Edit FAQ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="editForm" action="{{ route('faq.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="faqID" name="id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="question" class="form-label fw-bold">Question <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="question_edit" 
                                name="question" 
                                rows="3" 
                                placeholder="Enter the question here..." 
                                required></textarea>
                        </div>

                        <div class="col-12">
                            <label for="answer" class="form-label fw-bold">Answer <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="answer_edit" 
                                name="answer" 
                                rows="4" 
                                placeholder="Provide a detailed answer..." 
                                required></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 border-top pt-3">
                        <button type="submit" id="saveBtn" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('Admin.faq.script')