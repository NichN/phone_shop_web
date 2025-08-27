<div class="text-center">
    @if(!(auth()->user()->id == $row->id && auth()->user()->role_id == 1))
        <button class="btn btn-warning btn-sm editUserBtn" data-id="{{ $row->id }}">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-danger btn-sm delete-btn" data-url="{{ route('user.destroy', $row->id) }}">
            <i class="fas fa-trash-alt"></i>
        </button>
    @else
        <span class="text-muted small">Cannot edit own account</span>
    @endif
</div>
