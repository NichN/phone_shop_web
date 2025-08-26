
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pr_detail.index') }}",
       order: [[0, 'desc']],
        columns: [
            {
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
          { 
                data: 'images', 
                name: 'images',
                render: function(data, type, row) {
                    let decodedData = decodeHTML(data);
                    let images = JSON.parse(decodedData);
                    if (images && images.length > 0) {
                        return `<img src="/storage/${images[0]}" alt="${row.product_name}" style="width: 50px; height: 50px; object-fit: cover;">`;
                    } else {
                        return '<img src="/default-placeholder.jpg" alt="No Image" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                }
            },
            { data: 'product_name', name: 'product_name' },
            { data: 'brand', name: 'brand' },
            { data: 'category', name: 'category' },
            { data:'type', name: 'type'},
            {data: 'color_code',name: 'color_code',
                render: function(data, type, row) {
                    if (!data) return '';

                    return `
                        <span class="d-inline-block rounded-circle" 
                            style="width: 20px; height: 20px; background-color: ${data}; border: 1px solid #ccc;">
                        </span>
                    `;
                }
            },
            { data: 'size',name:'size'},
            { data: 'stock', name: 'stock' },
            { 
                data: 'price', 
                name: 'price',
                render: function(data, type, row) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            { data: 'warranty', name:'warranty'},
            {
                data: 'is_featured',
                name: 'is_featured',
                render: function(data, type, row) {
                    var checked = data ? 'checked' : '';
                    return `
                        <label class="switch">
                            <input type="checkbox" class="toggle-featured" data-id="${row.id}" ${checked}>
                            <span class="slider round"></span>
                        </label>
                    `;
                }
            },

            { data: 'action', orderable: false, searchable: false }
        ]
    });
            function decodeHTML(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }
    $('#ProName').select2({
        placeholder: "Search Product Name...",
        allowClear: true,
        maximumSelectionLength: 1 
    });
    $('#edit_ProName').select2({
    placeholder: "Search Product Name...",
    allowClear: true,
    maximumSelectionLength: 1,
    dropdownParent: $('#editModal')
});
    function loadProduct(searchTerm = '') {
        $.ajax({
            url: "{{ route('pr_detail.search-product') }}",
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                $('#ProName').empty().append('<option value="">Select Product</option>');
                response.forEach(function(product) {
                    $('#ProName').append(
                        `<option value="${product.id}">${product.name}</option>`
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error loading Product:", error);
            }
        });
    }
    loadProduct();

        $(document).on('click', '.editProduct_dt', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $("#pro_dt").val(id);

        let url = "{{ route('pr_detail.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            console.log('Edit API Response:', data);
            $('#pro_dt').val(data.id);
            loadProduct_edit('', data.id, data.product_name);
            $('#edit_brandName').val(data.brand_name);
            // console.log(data.brand_name); // Clear other fields
            $('#edit_Category').val(data.category_name);
            $('#edit_costPrice').val(data.cost_price || '');
            $('#edit_sellPrice').val(data.price || '');
            $('#edit_type').val(data.type || '');
            $('#edit_color_id').val(data.color_id || '');
            $('#edit_size_id').val(data.size_id || '');
            $('#edit_warranty').val(data.warranty || '');
            $('#imagePreview').empty();
            let images = data.images ? (typeof data.images === 'string' ? JSON.parse(data.images) : data.images) : [];
            if (images.length) {
                images.forEach(img => {
                    $('#imagePreview').append(
                        `<img src="/storage/${img}" class="img-thumbnail me-2" style="width:100px;height:100px;object-fit:cover;">`
                    );
                });
            } else {
                $('#imagePreview').append('<p>No images available</p>');
            }

            $('#editModal').modal('show');
            $('#editPr').data('id', data.id);
        }).fail(function(xhr) {
            console.error('Edit API Error:', xhr.responseJSON);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'An error occurred while fetching product data'
            });
        });
    });
    $('#editPr').on('submit', function(e) {
    e.preventDefault();
    let id = $('#pro_dt').val();
    let url = "{{ route('pr_detail.update', ':id') }}".replace(':id', id);
    let formData = new FormData(this);

    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Product updated successfully!',
                    willClose: () => {
                        table.ajax.reload(null, false);
                        $('#editModal').modal('hide');
                        $('#editPr')[0].reset(); // Reset the form here
                    }
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'An error occurred'
            });
        }
    });
});


    $(document).on('click', '.delete', function() {
        var productID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('pr_detail.delete', ':id') }}".replace(':id', productID),
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', 'The product has been deleted.', 'success');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete.', 'error');
                    }
                });
            }
        });
    });

    // Add product form submission
    $('#proForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product added successfully!',
                        willClose: () => {
                            window.location.href = "{{ route('pr_detail.index') }}";
                        }
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred'
                });
            }
        });
    });
    // $('#edit_ProName').on('change', function() {
    //     let productID = $(this).val();
    //     if (productID) {
    //         $.ajax({
    //             url: "{{ route('pr_detail.get_product', '') }}/" + productID,
    //             method: 'GET',
    //             success: function(data) {
    //                 $('#edit_brandName').val(data.brand || '');
    //                 $('#edit_Category').val(data.category || '');
    //                 $('#edit_costPrice').val(data.cost_price || '');
    //                 $('#edit_sellPrice').val(data.price || '');
    //                 $('#edit_type').val(data.type || '');
    //                 $('#edit_color_id').val(data.color_id || '');
    //                 $('#edit_size_id').val(data.size_id || '');
    //                 $('#edit_warranty').val(data.warranty || '');
    //                 $('#imagePreview').empty();
    //                 let images = data.images ? (typeof data.images === 'string' ? JSON.parse(data.images) : data.images) : [];
    //                 if (Array.isArray(images) && images.length > 0) {
    //                     images.forEach((img, index) => {
    //                         $('#imagePreview').append(
    //                             `<div class="position-relative"><img src="/storage/${img}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" alt="Image ${index + 1}"></div>`
    //                         );
    //                     });
    //                 } else {
    //                     $('#imagePreview').append('<p>No images available</p>');
    //                 }
    //             },
    //             error: function(xhr) {
    //                 console.error("Failed to fetch product data", xhr);
    //                 $('#edit_brandName').val('');
    //                 $('#edit_Category').val('');
    //                 $('#edit_costPrice').val('');
    //                 $('#edit_sellPrice').val('');
    //                 $('#edit_type').val('');
    //                 $('#edit_color_id').val('');
    //                 $('#edit_size_id').val('');
    //                 $('#edit_warranty').val('');
    //                 $('#imagePreview').empty().append('<p>No images available</p>');
    //             }
    //         });
    //     } else {
    //         $('#edit_brandName').val('');
    //         $('#edit_Category').val('');
    //         $('#edit_costPrice').val('');
    //         $('#edit_sellPrice').val('');
    //         $('#edit_type').val('');
    //         $('#edit_color_id').val('');
    //         $('#edit_size_id').val('');
    //         $('#edit_warranty').val('');
    //         $('#imagePreview').empty().append('<p>No images available</p>');
    //     }
    // });

});
function changeprduct(selectElement) {
    let productID = $(selectElement).val();
    if (productID) {
        $.ajax({
            url: "{{ route('pr_detail.get_product', '') }}/" + productID,
            method: 'GET',
            success: function(data) {
                if (selectElement.id === 'ProName') {
                    $('#brandName').val(data.brand);
                    $('#Category').val(data.category);
                } else if (selectElement.id === 'edit_ProName') {
                    $('#edit_brandName').val(data.brand);
                    $('#edit_Category').val(data.category);
                }
            },
            error: function(xhr) {
                console.error("Failed to fetch product data", xhr);
            }
        });
    }
}
function loadProduct_edit(searchTerm = '', currentProductId = null, currentProductName = null) {
    $.ajax({
        url: "{{ route('pr_detail.search-product') }}",
        method: 'GET',
        data: { search: searchTerm },
        success: function(response) {
            $('#edit_ProName').empty().append('<option value="">Select Product</option>');
            // Add current product if provided (to ensure it’s available)
            if (currentProductId && currentProductName) {
                $('#edit_ProName').append(
                    `<option value="${currentProductId}" selected>${currentProductName}</option>`
                );
            }
            // Add other products
            response.forEach(function(product) {
                if (product.id != currentProductId) {
                    $('#edit_ProName').append(
                        `<option value="${product.id}">${product.name}</option>`
                    );
                }
            });
            // Trigger select2 refresh
            $('#edit_ProName').trigger('change');
        },
        error: function(xhr, status, error) {
            console.error("Error loading Edit Product:", error);
        }
    });
}

    loadProduct_edit();
    $(document).on('click', '.viewProduct', function () {
    const itemId = $(this).data('id');
    var url = "{{ route('pr_detail.product_items', ':id') }}".replace(':id', itemId);
    
    $.ajax({
        url: url,
        method: 'GET',
        success: function (response) {
            // Basic Details
            $('#product_name').text(response.product_name);
            $('#viewModalLabel').text(response.product_name);
            $('#warrantyOptions').text(response.warranty);
            $('#stock_qty').text(response.stock);
            $('#product_price').text(`$${response.price}`);

            $('#colorOptions').empty();
            if (response.color) {
                const colorCode = response.color;
                const colorId = colorCode.replace('#', '');
                const input = `
                    <input type="radio" class="btn-check" name="color" id="color_${colorId}" value="${colorCode}" checked disabled />
                `;
                const label = `
                    <label class="btn d-flex align-items-center gap-2 disabled" for="color_${colorId}">
                        <span style="display:inline-block; width:30px; height:30px; background-color:${colorCode}; border-radius:50%;"></span>
                    </label>
                `;
                $('#colorOptions').append(input + label);
            }
            
            $('#sizeOptions').empty();
            if (response.size) {
                const size = response.size;
                const input = `
                    <input type="radio" class="btn-check" name="size" id="size_${size}" value="${size}" checked disabled />
                `;
                const label = `
                    <label class="btn btn-outline-primary disabled" for="size_${size}">
                        ${size}
                    </label>
                `;
                $('#sizeOptions').append(input + label);
            }

            // Images
            const images = response.images || [];
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
            $('#viewDetailModal').modal('show');
        },
        error: function () {
            alert('This Product Item is not available');
        }
    });
});

function changeImage(imgElement) {
    const newSrc = $(imgElement).attr('src');
    $('#mainProductImage').attr('src', newSrc);
    $('.thumbnail-img').removeClass('selected-thumbnail');
    $(imgElement).addClass('selected-thumbnail');
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.toggle-featured', function() {
    var isActive = $(this).prop('checked') ? 1 : 0;
    var productId = $(this).data('id');
    console.log('Toggle Featured:', isActive);
    console.log('Product ID:', productId);
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({
    url: `/update-featured-status/${productId}`,
    type: 'POST',
    data: {
        is_active: isActive
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    // success: function(response) {
    //     if (response.success) {
    //         alert('Product status updated successfully!');
    //     } else {
    //         alert('Error updating product status: ' + (response.message || 'Unknown error'));
    //     }
    // },
    error: function(xhr) {
        console.log('Error Response:', xhr.responseText);
        alert('An error occurred while updating product status: ' + xhr.status);
    }
});
});

</script>