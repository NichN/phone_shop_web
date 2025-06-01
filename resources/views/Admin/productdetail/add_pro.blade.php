@include('Admin.component.sidebar')
<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="w3-main">
    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Add Product</h5>
            </div>
            <div class="card-body">
                <form id="proForm" action="{{ route('pr_detail.store') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="name" id="Name">
                    <input type="hidden" name="size_id" id="size_id">
                    <input type="hidden" name="color_id" id="color_id">
                    <input type="hidden" name="stock" id ="stock">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <select class="select2 js-states form-select" multiple="multiple" id="ProName" name="product_name" required onchange="changeprduct(this)">
                                <option value="">Choose Name</option>
                                @foreach($product as $products)
                                <option value="{{ $products->id }}">{{ $products->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Brand <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="brandName" name="brandName" placeholder="" required onchange="localStorage.setItem('brandName', this.value)">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label for="brandName" class="form-label fw-bold"> Category <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Category" name="Category" placeholder="" required onchange="localStorage.setItem('Category', this.value)">
                        </div>
                        <div class="col-md-2">
                            <label for="brandName" class="form-label fw-bold"> Cost Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="costPrice" name="cost_price" placeholder="Enter Cost Price" required>
                        </div>
                        <div class="col-md-2">
                            <label for="brandName" class="form-label fw-bold"> Sell Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control " id="sellPrice" name="price" placeholder="Enter Sell Price" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="form-group col-md-5">
                            <label for="color" class="form-label fw-bold">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="color_id" name="color">
                                <option value="">Choose Color</option>
                                @foreach($color as $colors)
                                    <option value="{{ $colors->id }}">{{ $colors->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="color" class="form-label fw-bold">Size <span class="text-danger">*</span></label>
                            <select class="form-select" id="size_id" name="size">
                                <option value="">Choose Size</option>
                                @foreach($size as $sizes)
                                    <option value="{{ $sizes->id }}">{{ $sizes->size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-10">
                            <label for="images" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required onchange="previewImages(this)">
                            <small class="form-text text-muted">You can select multiple images.</small>
                        </div>
                    </div>
                    <div id="imagePreview" class="d-flex flex-wrap gap-3 mt-3"></div>
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
@include('Admin.productdetail.script');
<script>
function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview-item position-relative';
                previewDiv.dataset.index = index;
                
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="height: 120px; width: auto;">
                    <button type="button" class="btn btn-danger btn-sm remove-image" style="position: absolute; top: 5px; right: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(previewDiv);
                previewDiv.querySelector('.remove-image').addEventListener('click', () => removeImage(index));
            };
            
            reader.readAsDataURL(file);
        });
    }
}
function removeImage(index) {
    const input = document.getElementById('images');
    const files = Array.from(input.files);
    const newFiles = files.filter((_, i) => i !== index);
    const dataTransfer = new DataTransfer();
    newFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    previewImages(input);
}
</script>
