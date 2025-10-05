
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function decodeHTML(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

var table = $('.data-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('products.product_index') }}",
    order: [[0, 'desc']],
    columns: [
        {
            data: 'id',
            name: 'id',
            render: function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        { 
            data: 'images', 
            name: 'images',
            render: function(data, type, row) {
            if (!data) { 
                return '<img src="/default-placeholder.jpg" alt="No Image" style="width: 50px; height: 50px; object-fit: cover;">';
            }
            let decodedData = decodeHTML(data);
            let images = [];
            try {
                images = JSON.parse(decodedData);
            } catch (e) {
                console.warn('Invalid JSON for images:', decodedData);
                return '<img src="/default-placeholder.jpg" alt="No Image" style="width: 50px; height: 50px; object-fit: cover;">';
            }

            if (images.length > 0) {
                return `<img src="/storage/${images[0]}" alt="${row.product_name}" style="width: 50px; height: 50px; object-fit: cover;">`;
            } else {
                return '<img src="/default-placeholder.jpg" alt="No Image" style="width: 50px; height: 50px; object-fit: cover;">';
            }
        }

        },
        { data: 'name', name: 'name' },
        {
                    data: 'colors_code', name: 'colors_code',
                    render: function (data) {
                        if (Array.isArray(data)) {
                            return data.map(function (color) {
                                return `<span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:${color};border:1px solid #ccc;margin-right:3px;"></span>`;
                            }).join('');
                        }
                        return data;
                    }
                },
                {
                    data: 'sizes', name: 'sizes',
                    render: function (data) {
                        return Array.isArray(data) ? data.join(', ') : data;
                    }
                },
        { data: 'brandname', name: 'brandname' },
        { data: 'categoryname', name: 'categoryname' },
        { data: 'action', orderable: false, searchable: false }
    ]
});


    // Product Add Form Submission
    if ($('#productForm').length) {
        $('#productForm').on('submit', function(e) {
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
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Product added successfully!',
                        });
                        $('#productForm')[0].reset();
                        $('.data-table').DataTable().ajax.reload(null, false);
                        $('#productForm select').val(null).trigger('change');
                        $('#addModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred',
                    });
                }
            });
        });
    }

    // Delete Product
    $('body').on('click', '.deleteProduct', function() {
        let id = $(this).data('id');
        let url = "{{ route('products.deleteproduct', ':id') }}".replace(':id', id);

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product deleted successfully!',
                        willClose: () => location.reload()
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete product.'
                });
            }
        });
    });

    // View Product Redirect
    $(document).on('click', '.viewProduct', function() {
        const url = $(this).data('url');
        if (url) {
            window.location.href = url;
        } else {
            alert('URL missing');
        }
    });
    $(document).on('click', '.editProduct', function() {
        const url = $(this).data('url');
        if (url) {
            window.location.href = url;
        } else {
            alert('URL missing');
        }
    });

    // Load Products into Edit Dropdown (used in edit modal)
    function loadProduct_edit(searchTerm = '', currentProductId = null, currentProductName = null) {
        $.ajax({
            url: "{{ route('pr_detail.search-product') }}",
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                $('#edit_ProName').empty().append('<option value="">Select Product</option>');

                // Add current product as selected if available
                if (currentProductId && currentProductName) {
                    $('#edit_ProName').append(
                        `<option value="${currentProductId}" selected>${currentProductName}</option>`
                    );
                }

                // Add other products excluding current one
                response.forEach(function(product) {
                    if (product.id != currentProductId) {
                        $('#edit_ProName').append(
                            `<option value="${product.id}">${product.name}</option>`
                        );
                    }
                });

                // Refresh select (if using Select2 or similar)
                $('#edit_ProName').trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("Error loading Edit Product:", error);
            }
        });
    }
    loadProduct_edit(); // initial load

    // Edit Variant Click (delegated for dynamic elements)
    $(document).on('click', '.editVariant_dt', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        $("#pro_dt").val(id);

        let url = "{{ route('pr_detail.edit', ':id') }}".replace(':id', id);

        $.get(url, function(data) {
            console.log('Edit API Response:', data);
            $('#pro_dt').val(data.id);
            loadProduct_edit('', data.id, data.product_name);
            $('#edit_brandName').val(data.brand_name);
            $('#edit_Category').val(data.category_name);
            $('#edit_costPrice').val(data.cost_price || '');
            $('#edit_sellPrice').val(data.price || '');
            $('#edit_type').val(data.type || '');
            $('#edit_color_id').val(data.color_id || '');
            $('#edit_size_id').val(data.size_id || '');
            $('#edit_warranty').val(data.warranty || '');

            // Clear previous images
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

            // Show modal (Bootstrap 5 example)
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();

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

    // Delete Variant (delegated)
    $(document).on('click', '.deleteVariant', function() {
        const id = $(this).data('id');

        if (confirm("Are you sure you want to delete this variant?")) {
            $.ajax({
                url: '/product-detail/delete/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    alert('Variant deleted successfully');
                    location.reload();
                },
                error: function() {
                    alert('Failed to delete variant.');
                }
            });
        }
    });

});
</script>
