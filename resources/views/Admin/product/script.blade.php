<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script>
    $(document).ready(function(){
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.product_index')}}",
            columns:[
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'brandname', name: 'brandname' },
                { data: 'categoryname', name: 'categoryname' },
                { data: 'description' , name:'description'},
                { data: 'action', orderable: false, searchable: false }
            ]
        });
        if($('#productForm').length){
            $('#productForm').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Product added successfully!',
                            });

                            $('.data-table').DataTable().ajax.reload(null, false);
                            $('#addModal').modal('hide');
                        }
                    },
                    error: function(xhr){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'An error occurred'
                        });
                    }
                });
            });
        }
        });
        $('body').on('click','.deleteProduct',function(){
            let id = $(this).data('id');
            let url ="{{route('products.deleteproduct', ':id')}}";
            if(confirm("Are You Sure ?")){
                $.ajax({
                    url:url.replace(':id', id),
                    type:'DELETE',
                    dataType:'json',
                    data:{_token:'{{ csrf_token() }}'},
                    success:function(response){
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Color deleted successfully!',
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                })
            }
        });
        $('body').on('click', '.editProduct', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $("#productId").val(id);
        let url = "{{ route('products.product_edit', ':id') }}".replace(':id', id);
            $.get(url,function(data) {
                    $('#productId').val(data.id);
                    $('#edit_prName').val(data.name);
                    $('#edit_brand_id').val(data.brand_id);
                    $('#edit_description').val(data.description);
                    $('#edit_cat_id').val(data.cat_id);
                    $('#editModal').modal('show');
                    $('#editForm').data('id', data.id);
                }
            ).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            });
        });
        $('#editForm').submit(function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = "{{ route('products.product_update', ':id') }}".replace(':id', id);
        $.ajax({
            url,
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            dataType: 'json',
            success(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Size updated successfully!',
                        willClose: () => window.location.href = "{{ route('products.product_index') }}"
                    });
                }
            },
            error(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });
    $(document).on('click', '.viewProduct', function () {
        const productId = $(this).data('id');
        var url = "{{ route('pr_detail.product_items', ':id') }}".replace(':id', productId)
        $.ajax({
            url: url,
            method: 'GET',
            success: function (response) {
                $('#product_name').text(response.product_name);
                $('#viewModalLabel').text(response.product_name);
                $('#stock_qty').text(response.stock);
                $('#colorOptions').empty();
                response.colors.forEach(function (color, index) {
                    if (!color) return;

                    const cleanCode = color.toLowerCase();
                    const colorId = cleanCode.replace('#', '');
                    const input = `
                        <input type="radio" class="btn-check" name="color" id="color_${colorId}" value="${cleanCode}" autocomplete="off" ${index === 0 ? 'checked' : ''} />
                    `;
                    const label = `
                        <label class="btn d-flex align-items-center gap-2" for="color_${colorId}">
                            <span style="display:inline-block; width:30px; height:30px; background-color:${cleanCode}; border-radius:50%;"></span>
                            ${cleanCode}
                        </label>
                    `;
                    $('#colorOptions').append(input + label);
                });

                $('#sizeOptions').empty();
                response.sizes.forEach(function (size, index) {
                    const input = `
                        <input type="radio" class="btn-check" name="size" id="size_${size}" value="${size}" autocomplete="off" ${index === 0 ? 'checked' : ''} />
                    `;
                    const label = `
                        <label class="btn btn-outline-dark" for="size_${size}">
                            ${size}
                        </label>
                    `;
                    $('#sizeOptions').append(input + label);
                });
                const allImages = response.images || [];
                if (allImages.length > 0) {
                    $('#mainProductImage').attr('src', '/storage/' + allImages[0]);
                } else {
                    $('#mainProductImage').attr('src', '/default-placeholder.jpg');
                }

                $('#thumbnailGallery').empty();
                allImages.forEach((imgPath, index) => {
                    const thumb = `
                        <div class="col-3">
                            <img src="/storage/${imgPath}" class="thumbnail-img img-fluid ${index === 0 ? 'selected-thumbnail' : ''}" 
                                 alt="Thumbnail ${index + 1}" onclick="changeImage(this)">
                        </div>
                    `;
                    $('#thumbnailGallery').append(thumb);
                });
                let allVariants = response.variants;

                function updateVariantDisplay() {
                    const selectedSize = $('input[name="size"]:checked').val();
                    const variant = allVariants.find(item => item.size === selectedSize);

                    if (variant) {
                        $('#product_price').text(`$${variant.price}`);

                        const images = variant.images || [];
                        if (images.length > 0) {
                            $('#mainProductImage').attr('src', '/storage/' + images[0]);
                        } else {
                            $('#mainProductImage').attr('src', '/default-placeholder.jpg');
                        }

                        $('#thumbnailGallery').empty();
                        images.forEach((imgPath, index) => {
                            const thumb = `
                                <div class="col-3">
                                    <img src="/storage/${imgPath}" class="thumbnail-img img-fluid ${index === 0 ? 'selected-thumbnail' : ''}" 
                                         alt="Thumbnail ${index + 1}" onclick="changeImage(this)">
                                </div>
                            `;
                            $('#thumbnailGallery').append(thumb);
                        });
                    } else {
                        $('#product_price').text('N/A');
                        $('#mainProductImage').attr('src', '/default-placeholder.jpg');
                        $('#thumbnailGallery').empty();
                    }
                }
                $(document).off('change.sizeOnly').on('change.sizeOnly', 'input[name="size"]', updateVariantDisplay);
                updateVariantDisplay();
                $('#viewDetailModal').modal('show');
            },
            error: function () {
                alert('This Product are not in stock yet');
            }
        });
    });
    function changeImage(imgElement) {
        const newSrc = $(imgElement).attr('src');
        $('#mainProductImage').attr('src', newSrc);
        $('.thumbnail-img').removeClass('selected-thumbnail');
        $(imgElement).addClass('selected-thumbnail');
    }
    
</script>
