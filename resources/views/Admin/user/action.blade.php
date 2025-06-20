<div class="text-center">
    <button class="btn btn-warning btn-sm editUserBtn" data-id="{{ $row->id }}">
        <i class="fas fa-edit"></i>
    </button>
    <button class="btn btn-danger btn-sm delete-btn" data-url="{{ route('user.destroy', $row->id) }}">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
