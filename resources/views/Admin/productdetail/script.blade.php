<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('pr_detail.index') }}",
        columns: [
            {
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'product_name', name: 'product_name' },
            { data: 'brand', name: 'brand' },
            { data: 'category', name: 'category' },
            { data: 'stock', name: 'stock' },
            { data: 'price', name: 'price' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
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

           $('#edit_ProName').append(new Option( data.pro_id, true, true)).trigger('change');
            $('#edit_costPrice').val(data.cost_price);
            $('#edit_sellPrice').val(data.price);
            $('#edit_brandName').val(data.brand).trigger('change');
            $('#edit_Category').val(data.catgory).trigger('change');
            $('#edit_color_id').val(data.color_id);
            $('#edit_size_id').val(data.size_id);


            $('#editModal').modal('show');
            $('#editPr').data('id', data.id);
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'An error occurred'
            });
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
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
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
    $('#edit_form').on('submit', function(e) {
        e.preventDefault();
        let id = $('#productId').val();
        let url = "{{ route('pr_detail.update', ':id') }}".replace(':id', id);
        let formData = new FormData(this);
        formData.append('_method', 'PUT');
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
function loadProduct_edit(searchTerm = '') {
        $.ajax({
            url: "{{ route('pr_detail.search-product') }}",
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                $('#ProName').empty().append('<option value="">Select Product</option>');
                response.forEach(function(product) {
                    // $('#ProName').append(
                    //     `<option value="${product.id}">${product.name}</option>`
                    // );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error loading Product:", error);
            }
        });
    }
    loadProduct_edit();
</script>